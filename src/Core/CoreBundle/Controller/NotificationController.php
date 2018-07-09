<?php
namespace Core\CoreBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Core\CoreBundle\Entity\Notification;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class NotificationController extends Controller {

    /**
     * @Template()
     */
    public function topMenuAction() {
        $data = array();

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $user->setLastLogin(new \DateTime());

        $role = '%' . $user->getRoles()[0] . '%';
        $data['notifications'] = $em->getRepository("CoreBundle:Notification")->createQueryBuilder('n')
            ->where('n.new = :new')
            ->andWhere('n.group LIKE :group or n.user = :user')
            ->orderBy('n.dateCreated', 'DESC')
            ->setParameter('new', '1')
            ->setParameter('group', $role)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $data;
    }


    /**
     * @Template()
     * @param $id
     * @return array|JsonResponse
     */
    public function showAction($id) {
        $data = [];
        $notification = $this->get('core.notification');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $role = '%' . $user->getRoles()[0] . '%';

        $data['notification'] = $em->getRepository("CoreBundle:Notification")->createQueryBuilder('n')
            ->where('n.new = :new')
            ->andWhere('n.id = :id')
            ->andWhere('n.group LIKE :group or n.user = :user')
            ->orderBy('n.dateCreated', 'DESC')
            ->setParameter('new', '1')
            ->setParameter('group', $role)
            ->setParameter('user', $user)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if ( ! $data['notification']) {
            return new NotFoundHttpException();
        }

        $notification->notNew($id);
        $data = new JsonResponse($data);
        return $data;
    }
}