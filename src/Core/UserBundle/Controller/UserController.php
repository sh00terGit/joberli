<?php
namespace Core\UserBundle\Controller;

use Core\UserBundle\Entity\User;
use Core\UserBundle\Entity\UserData;
use Core\UserBundle\Form\UserAdminType;
use Core\UserBundle\Form\UserSettingsType;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends Controller
{

    /**
     * @Template()
     * @Security("has_role( 'ROLE_SUPER_ADMIN')")
     */
    public function managerAction()
    {
        $data = array();

        $data['page']['title'] = 'Управление пользователями';

        $data['users'] = $this->getDoctrine()->getRepository('UserBundle:User')
            ->findAll();


        return $data;
    }

    /**
     * @Template()
     * @Security("has_role( 'ROLE_USER')")
     */
    public function statAction()
    {
        $data = array();
        $userStat = $this->get('user.stat');
        $adStat = $this->get('ad.manager.stat');
        $level = $this->get('ad.manager.level');

        $user = $this->getUser();
        $data['page']['title'] = 'Статистика пользователя';

        $data['stat']['rating'] = $adStat->userRating($user);
        $data['stat']['completeOrders'] = $adStat->completedOrders($user);

        $data['stat']['daysOnSite'] = $userStat->getDaysOnSite($user);

        $users = $this->getDoctrine()->getRepository('UserBundle:User')->findAll();

        foreach ($users as $user) {
            $adStat->updateRatingUser($user);
            $level->update($user);
        }

        return $data;
    }
    /**
     * @param $id
     * @Template
     * @return array
     */
    public function indexAction($id)
    {
        $data = [];

        $user = $this->getDoctrine()->getRepository('UserBundle:User')->find($id);
        $userCurrent = $this->getUser();

        if ( ! $user) {
            throw $this->createNotFoundException('Пользователь не найден.');
        }

        $data['user'] = $user;
        $data['page']['title'] = $user->getUsername();

        if ( ! $userCurrent || $userCurrent->getId() != $user->getId()) {
            return $data;
        }

        $data['avatar_form'] = $this->createEditSettingsForm($user->getUserData(), 'avatar')->createView();
        $data['cover_form'] = $this->createEditSettingsForm($user->getUserData(), 'cover')->createView();
        return $data;

    }

    /**
     * @return array
     * @Template()
     */
    public function dashBoardAction() {
        $data = [];
        $data['page']['userMenu'] = true;
        $data['page']['title'] = 'Панель управления';
        return $data;
    }



    /**
     * @Template()
     */
    public function settingsAction(Request $request)
    {
        $data = [];
        $data['page']['userMenu'] = true;
        /**
         * @var $user User
         */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }


        $userData = $this->getDoctrine()->getRepository('UserBundle:UserData')
            ->findOneBy(
                array(
                    'user' =>$user->getId()
                )
            );

        if ( ! $userData) {
            $userData = new UserData();
            $userData->setUser($user);
        }

        $form = $this->createEditSettingsForm($userData);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($userData);
            $em->flush();
            return $this->redirect($this->generateUrl('user_page', array('id' => $user->getId())));
        }


        $data['page']['title'] = 'Настройки профиля';
        $data['form'] = $form->createView();

        return $data;
    }

    /**
     * Creates a form to edit a Ad entity.
     *
     * @param UserData $userData
     * @param string $type
     * @return \Symfony\Component\Form\Form The form
     * @internal param UserData $entity The entity
     *
     */
    private function createEditSettingsForm(UserData $userData, $type = 'user')
    {
        $form = $this->createForm(new UserSettingsType($type), $userData, array(
            'action' => $this->generateUrl('user_settings_page'),
            'method' => 'PUT',
        ));
        return $form;
    }



    /**
     * @param Request $request
     * @param integer $id
     * @Template
     * @return array
     * @Security("has_role( 'ROLE_SUPER_ADMIN')")
     */
    public function editSettingsAction (Request $request, $id) {
        $data = array();
        $em = $this->getDoctrine()->getManager();

        $userData = $this->getDoctrine()->getRepository('UserBundle:UserData')
            ->find($id);

        if (!is_object($userData) || !$userData) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->createEditSettingsForm($userData);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($userData);
            $em->flush();
            $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        } else if ($request->getMethod() == 'POST' and $form->getErrorsAsString()) {
            $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        }



        $data['formSettings'] = $form->createView();
        return $data;
    }




    /**
     * @Template()
     * @Security("has_role( 'ROLE_SUPER_ADMIN')")
     */
    public function editAction(Request $request, $id)
    {
        $data = array();
        $em = $this->getDoctrine()->getManager();
        $roles = $this->container->getParameter('security.role_hierarchy.roles');

        $user = $this->getDoctrine()->getRepository('UserBundle:User')
            ->find($id);


        if (!is_object($user)) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }


        $form = $this->createEditForm($roles, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->redirect($this->generateUrl('user_edit', array('id' => $id)));
        } else if ($request->getMethod() == 'POST' and $form->getErrorsAsString()) {
            $data['error'] = $form->getErrorsAsString();
        }

        $data['user'] = $user;
        $data['form'] = $form->createView();

        $data['page']['title'] = 'Редактирование ' . $user->getUsername();
        return $data;
    }


    /**
     * @param $roles
     * @param User $user
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm($roles, User $user)
    {
        $form = $this->createForm(new UserAdminType($roles), $user, array(
            'action' => $this->generateUrl('user_edit', array('id'=>$user->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Сохранить'));

        return $form;
    }
}