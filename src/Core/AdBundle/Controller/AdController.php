<?php

namespace Core\AdBundle\Controller;

use Core\AdBundle\Entity\AdSub;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Core\AdBundle\Entity\Ad;
use Core\AdBundle\Entity\AdAttach;
use Core\AdBundle\Entity\AdCategory;
use Core\AdBundle\Form\AdType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;

/**
 * Ad controller.
 *
 */
class AdController extends Controller
{
    /**
     * Lists all Ad entities.
     * @Template()
     */
    public function indexAction()
    {
        $data = array();

        $data['page']['title'] = 'Объявления';

        $em = $this->getDoctrine()->getManager();

        $data['entities'] = $em->getRepository('AdBundle:Ad')->findBy(array(), array(), 16);

        return $data;
    }


    /**
     * Lists all Ad entities.
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function favoriteAction()
    {
        $data = array();
        $data['user'] = $this->getUser();
        $data['page']['title'] = 'Избранные объявления';

        return $data;
    }

    /**
     * @param $categoryId integer
     * @Template()
     * @return array
     */
    public function showsCategoryAction($categoryId)
    {
        $data = array();
        $em = $this->getDoctrine()->getManager();

        if (isset($_GET['q']) and $_GET['q']) {
            $keyWord = $_GET['q'];
        } else
            $keyWord = false;

        $category = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
            ->find($categoryId);

        if (!$category) {
            throw $this->createNotFoundException('Категория не найдена.');
        }

        $where = 'ad.category = ' . $category->getId();
        if ($child = $category->getChild()) {
            foreach ($child as $item) {
                $where.= ' OR ad.category = ' . $item->getId();
            }
        }

        $qb= $em->getRepository("AdBundle:Ad")->createQueryBuilder('ad')
            ->where($where);



        if ($keyWord) {
            $qb->andWhere('ad.title LIKE :word')
            ->setParameter('word', '%' . $keyWord . '%');
            dump($keyWord);
        }

        $data['ads'] = $qb
            ->orderBy('ad.dateCreated', 'DESC')
            ->getQuery()
            ->getResult();

        return $data;
    }


    /**
     * Lists all Ad entities.
     * @Template
     * @Security("has_role( 'ROLE_SUPER_ADMIN')")
     */
    public function managerAction()
    {

        $data = array();
        $data['page']['title'] = 'Менеджер управления объявлениями';

        $em = $this->getDoctrine()->getManager();

        $data['entities'] = $em->getRepository('AdBundle:Ad')->findAll();

        return $data;
    }

    /**
     * @param $id
     * @return array
     * @Template
     */
    public function getByUserAction($id)
    {
        $data = [];

        $user = $this->getDoctrine()->getRepository('UserBundle:User')
            ->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Пользователь не найден');
        }

        $data['ads'] = $this->getDoctrine()->getRepository('AdBundle:Ad')
            ->findBy(
                array(
                    'userCreate' => $user
                ),
                array(
                    'dateCreated' => 'desc'
                )
            );

        return $data;
    }

    /**
     * Creates a new Ad entity.
     *@Security("has_role( 'ROLE_USER')")
     */
    public function createAction(Request $request)
    {
        $data = array();

        $tagManager = $this->get('fpn_tag.tag_manager');
        $photos = $request->request->get('photos');
        $categoryRequest = $request->request->get('category');
        $category = null;

        if ($categoryRequest) {
            $category = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
                ->find($categoryRequest);
        }


        $data['entity'] = new Ad();
        $data['form'] = $this->createCreateForm($data['entity']);
        $data['form']->handleRequest($request);

        $data['category'] = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
            ->findBy(
                array(
                    'parent' => null
                )
            );


        if ($data['form']->isValid() and $category) {

            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();



            $data['entity']->setUserCreate($user);
            $data['entity']->setCategory($category);
            $em->persist($data['entity']);
            if ($photos) {
                foreach ($photos as $item) {
                    $photo_item = $this->getDoctrine()->getRepository('AdBundle:AdAttach')
                        ->findOneBy(
                            array(
                                'id' => $item,
                                'ad' => null
                            )
                        );

                    if (!$photo_item) {
                        continue;
                    }

                    $photo_item->setAd($data['entity']);
                    $em->persist($photo_item);
                }
            }

            $em->flush();
            $tagManager->saveTagging($data['entity']);
            return $this->redirect($this->generateUrl('ad_show', array('slug' => $data['entity']->getSlug()))); $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();



            $data['entity']->setUserCreate($user);
            $data['entity']->setCategory($category);
            $em->persist($data['entity']);
            if ($photos) {
                foreach ($photos as $item) {
                    $photo_item = $this->getDoctrine()->getRepository('AdBundle:AdAttach')
                        ->findOneBy(
                            array(
                                'id' => $item,
                                'ad' => null
                            )
                        );

                    if (!$photo_item) {
                        continue;
                    }

                    $photo_item->setAd($data['entity']);
                    $em->persist($photo_item);
                }
            }

            $em->flush();
            $tagManager->saveTagging($data['entity']);


            $notification->create(
                'admin_new_ad',
                'Новая объявление',
                'Пользователь добавил новое объявление',
                $data['entity']->getId(),
                'admin'
            );

            return $this->redirect($this->generateUrl('ad_show', array('slug' => $data['entity']->getSlug())));


        }

        if ($photos) {
            foreach ($photos as $item) {
                $data['photos'][] = $this->getDoctrine()->getRepository('AdBundle:AdAttach')
                    ->findOneBy(
                        array(
                            'id' => $item,
                            'ad' => null
                        )
                    );
            }
        }
        $data['form'] = $data['form']->createView();

        return $this->render('AdBundle:Ad:new.html.twig', $data);
    }

    /**
     * Creates a form to create a Ad entity.
     *
     * @param Ad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Ad $entity)
    {
        $user = $this->getUser();
        $form = $this->createForm(new AdType($user), $entity, array(
            'action' => $this->generateUrl('ad_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Создать'));

        return $form;
    }

    /**
     * Displays a form to create a new Ad entity.
     * @Template
     * @Security("has_role( 'ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        $data = array();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $tagManager = $this->get('fpn_tag.tag_manager');
        $level = $this->get('ad.manager.level');
        $level->update($user);

        $subAdsLevel = $level->subAds[$user->getLevel()];

        $category = null;
        $ad = new Ad();



        $data['page']['title'] = 'Добавить объявление';
        $data['page']['userMenu'] = true;


        $data['category'] = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
            ->findBy(
                array(
                    'parent' => null
                )
            );


        for ($i = 1; $i <= $subAdsLevel; $i++) {
            $adSub = 'adSub' . $i;

            $$adSub = new AdSub();
            $ad->addSubAd($$adSub);
        }

        $form = $this->createCreateForm($ad);
        $category = $request->get('category');
        if ($category) {
            $category = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
                ->find($category);
        }

        $form->handleRequest($request);
        if ($form->isValid() and $category) {

            $photos = $request->files->get('photos');
            foreach ($photos as $photo) {
                $ad->addPhoto($photo);
            }

            $photos = $ad->getPhotos();

            foreach ($photos as $item) {
                if ($item instanceof UploadedFile) {
                    $ad->removePhoto($item);
                }
            }

            $adSub = $ad->getSubAd();

            foreach ($adSub as $item) {
                $item->setAd($ad);
                if ( ! $item->getTitle()) {
                    $ad->removeSubAd($item);
                }
            }


            $ad->setUserCreate($user);
            $ad->setCategory($category);
            $em->persist($ad);

            $em->flush();
            $tagManager->saveTagging($ad);
            return $this->redirect($this->generateUrl('ad_show', array('slug' => $ad->getSlug())));
        }

        $data['form'] = $form->createView();

        return $data;
    }

    /**
     * Finds and displays a Ad entity.
     * @Template
     */
    public function showAction($slug)
    {
        $data = array();

        $em = $this->getDoctrine()->getManager();
        $tagManager = $this->get('fpn_tag.tag_manager');

        $ad = $em->getRepository('AdBundle:Ad')->findOneBy(array('slug' => $slug));
        if (!$ad) {
            throw $this->createNotFoundException('Unable to find Ad entity.');
        }

        $data['other']['user'] = $em->getRepository('AdBundle:Ad')->findBy(
            array('userCreate' => $ad->getUserCreate()),
            array('dateCreated' => 'DESC'),
            4
        );

        $data['other']['category'] = $em->getRepository('AdBundle:Ad')->findBy(
            array('category' => $ad->getCategory()),
            array('dateCreated' => 'DESC'),
            4
        );

        $tagManager->loadTagging($ad);

        $ad->setViews($ad->getViews() + 1);
        $em->persist($ad);
        $em->flush();

        $data['page']['title'] = $ad->getTitle();

        $data['entity'] = $ad;

        $data['delete_form'] = $this->createDeleteForm($data['entity']->getId())->createView();

        return $data;
    }

    /**
     * Displays a form to edit an existing Ad entity.
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template
     * @Security("has_role( 'ROLE_USER')")
     */
    public function editAction(Request $request, $id)
    {
        $data = [];
        $data['page']['title'] = 'Редактирование';
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $tagManager = $this->get('fpn_tag.tag_manager');

        $level = $this->get('ad.manager.level');
        $level->update($user);

        $subAdsLevel = $level->subAds[$user->getLevel()];

        $data['entity'] = $em->getRepository('AdBundle:Ad')->find($id);

        if (!$data['entity'] and $user == $data['entity']->getUserCreate()) {
            throw $this->createNotFoundException('Объявление не найдено.');
        }

        $categoryRequest = $request->request->get('category');

        if ($categoryRequest) {
            $category = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
                ->find($categoryRequest);
        }

        $subAdsLevel -= $data['entity']->getSubAd()->count();

        $tagManager->loadTagging($data['entity']);


        $data['category'] = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
            ->findBy(
                array(
                    'parent' => null
                )
            );

        if ($data['entity']->getCategory()->getParent()) {
            $data['category_child'] = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
                ->findBy(
                    array(
                        'parent' => $data['entity']->getCategory()->getParent()->getId()
                    )
                );
        }

        for ($i = 1; $i <= $subAdsLevel; $i++) {
            $adSub = 'adSub' . $i;

            $$adSub = new AdSub();
            $data['entity']->addSubAd($$adSub);
        }

        $editForm = $this->createEditForm($data['entity']);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $this->get('fpn_tag.tag_manager')->saveTagging($data['entity']);

            $photos = $request->files->get('photos');
            if ($photos) {
                foreach ($photos as $photo) {
                    $data['entity']->addPhoto($photo);
                }
            }
            $data['entity']->setCategory($category);

            $adSub = $data['entity']->getSubAd();

            foreach ($adSub as $item) {
                $item->setAd($data['entity']);
                if ( ! $item->getTitle()) {
                    $data['entity']->removeSubAd($item);
                }
            }


            $em->persist($data['entity']);
            $em->flush();

            return $this->redirect($this->generateUrl('ad_edit', array('id' => $id)));
        }

        $data['edit_form'] = $editForm->createView();
        $data['delete_form'] = $this->createDeleteForm($id)->createView();

        return $data;
    }

    /**
     * Creates a form to edit a Ad entity.
     *
     * @param Ad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Ad $entity)
    {
        $user = $this->getUser();
        $form = $this->createForm(new AdType($user), $entity, array(
            'action' => $this->generateUrl('ad_edit', array('id' => $entity->getId())),
            'method' => 'PUT'
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));


        return $form;
    }

    /**
     * Edits an existing Ad entity.
     */
    public function updateAction(Request $request, $id)
    {
        $data = array();
        $categoryRequest = $request->request->get('category');

        if ($categoryRequest) {
            $category = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
                ->find($categoryRequest);
        }
        $em = $this->getDoctrine()->getManager();

        $data['entity'] = $em->getRepository('AdBundle:Ad')->find($id);

        if (!$data['entity']) {
            throw $this->createNotFoundException('Unable to find Ad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($data['entity']);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $this->get('fpn_tag.tag_manager')->saveTagging($data['entity']);

            $photos = $request->files->get('photos');
            if ($photos) {
                foreach ($photos as $photo) {
                    $data['entity']->addPhoto($photo);
                }
            }
            $data['entity']->setCategory($category);

            $adSub = $data['entity']->getSubAd();

            foreach ($adSub as $item) {
                $item->setAd($data['entity']);
                if ( ! $item->getTitle()) {
                    $data['entity']->removeSubAd($item);
                }
            }


            $em->persist($data['entity']);
            $em->flush();

            return $this->redirect($this->generateUrl('ad_edit', array('id' => $id)));
        }

        $data['category'] = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
            ->findBy(
                array(
                    'parent' => null
                )
            );

        if ($data['entity']->getCategory()->getParent()) {
            $data['category_child'] = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
                ->findBy(
                    array(
                        'parent' => $data['entity']->getCategory()->getParent()->getId()
                    )
                );
        }

        $data['edit_form'] = $editForm->createView();
        $data['delete_form'] = $deleteForm->createView();

        return $data;
    }

    /**
     * Deletes a Ad entity.
     * @Security("has_role( 'ROLE_USER')")
     */
    public function deleteAction(Request $request, $id)
    {


        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdBundle:Ad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ad entity.');
            }

            foreach ($entity->getPhotos() as $item) {
                /**
                 * @var $item \Core\AdBundle\Entity\AdAttach
                 */
                $this->deletePhotoAction($item->getId());
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ad'));
    }

    /**
     * Creates a form to delete a Ad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }




    /**
     * @param Request $request
     * @return Response
     * @Method("POST")
     * @Security("has_role( 'ROLE_USER')")
     */
    public function CategoryLoadAction(Request $request)
    {

        $data = array();
        $data['category_form'] = null;

        if (!$request->isMethod('POST')) {
            return new Exception('не найдены POST данные');
        }

        $categoryId = $request->request->get('parentCategory');

        $categoryRepository = $this->getDoctrine()->getRepository('AdBundle:AdCategory');
        $category = $categoryRepository
            ->findOneBy(
                array(
                    'id' => $categoryId,
                    'parent' => null
                )
            );

        if (!$category) {
            return new Exception('Категория не найдена');
        }

        $data['child_category'] = $categoryRepository
            ->findBy(
                array(
                    'parent' => $category->getId()
                )
            );


        if ($data['child_category']) {
            $data['category_form'] = $this->renderView('AdBundle:Ad:CategoryLoad.html.twig', $data);
        }

        $data = json_encode($data);
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param $id int
     * @return Response
     * @Security("has_role( 'ROLE_USER')")
     */
    public function deletePhotoAction($id)
    {

        $photo = $this->getDoctrine()->getRepository('AdBundle:AdAttach')
            ->find($id);

        $em = $this->getDoctrine()->getManager();
        $cacheManager = $this->container->get('liip_imagine.cache.manager');
        $cacheManager->remove($photo->getPhoto()['path']);
        $em->remove($photo);
        $em->flush();

        $data = array();

        $data['success'] = 'true';
        $data = json_encode($data);
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param Request $request
     * @Template
     * @return array
     */
    public function searchAction (Request $request) {
        $data = [];

        $keyWord = $data['q'] = $request->query->get('q');
        $em = $this->getDoctrine()->getManager();

        if ( ! $keyWord) {
            $data['page']['title'] = 'Поиск по объявлениям';
            return $data;
        }
        $data['page']['title'] = 'Результаты поиска по запросу "' . $keyWord . '"';


        $ads = $em->getRepository('AdBundle:Ad')->createQueryBuilder('a')
            ->where('a.title LIKE :word')
            ->setParameter('word', '%' . $keyWord . '%')
            ->getQuery()
            ->getResult();

        if (! $ads) {
            return $data;
        }

        $data['ads'] = $ads;

        /**
         * @var $item Ad
         */
        foreach ($ads as $item) {
            $data['category'][$item->getCategory()->getId()][] = $item->getCategory();
        }


        return $data;
    }
}
