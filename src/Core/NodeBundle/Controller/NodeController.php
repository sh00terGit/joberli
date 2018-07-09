<?php

namespace Core\NodeBundle\Controller;

use Core\NodeBundle\Entity\NodeAttach;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Core\NodeBundle\Entity\Node;
use Core\NodeBundle\Form\NodeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;



use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class NodeController extends Controller
{

    /**
     * Lists all Node entities.
     *@Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('NodeBundle:Node')->findAll();

        return $this->render('NodeBundle:Node:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * @return array
     * @Template()
     */
    public function indexPageAction()
    {
        $data = [];
        $data['content'] = $this->getDoctrine()->getRepository('NodeBundle:Node')->findOneBy(
            array(
                'slug' => 'index'
            )
        );
        $data['last_news'] = $this->getDoctrine()->getRepository('NodeBundle:Node')->findBy(
            array(
                'category' => 2
            ),
            array(
                'id' => 'desc'
            ),
            5
        );

        return $data;
    }

    /**
     * Lists all Node entities.
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function managerAction()
    {
        $data = array();

        $data['page']['title'] = 'Менеджер управления контентом';

        $em = $this->getDoctrine()->getManager();

        $data['entities'] = $em->getRepository('NodeBundle:Node')->findBy(array(), array('dateCreated' => 'desc'));

        return $data;
    }


    /**
     * Creates a new Node entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Node();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $this->getUser();

            $entity->setUserCreate($user);
            $entity->setViews(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('node_show', array('slug' => $entity->getSlug(), 'category' => $entity->getCategory()->getId())));
        }

        return $this->render('NodeBundle:Node:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'photo_form' => $this->createPhotoForm(new NodeAttach())->createView()
        ));
    }

    /**
     * Creates a form to create a Node entity.
     *
     * @param Node $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Node $entity)
    {
        $form = $this->createForm(new NodeType(), $entity, array(
            'action' => $this->generateUrl('node_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Node entity.
     *@Security("has_role('ROLE_ADMIN')")
     */
    public function newAction()
    {
        

        $entity = new Node();
        $form = $this->createCreateForm($entity);

        return $this->render('NodeBundle:Node:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'photo_form' => $this->createPhotoForm(new NodeAttach())->createView()
        ));
    }

    /**
     * Finds and displays a Node entity.
     * @Template()
     * @param $category
     * @param $slug
     * @return array
     */
    public function showAction($category, $slug, $all_page = false)
    {
        $data = [];

        $em = $this->getDoctrine()->getManager();

        $data['category'] = $em->getRepository('NodeBundle:NodeCategory')->find($category);

        if (!$data['category']) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }

        $data['entity'] = $em->getRepository('NodeBundle:Node')->findOneBy(
            array(
                'slug' => $slug,
                'category' => $data['category']
            )
        );

        if (!$data['entity']) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }

        $data['page']['title'] = $data['entity']->getTitle();
        $data['entity']->setViews(($data['entity']->getViews() + 1));
        $em->persist($data['entity']);
        $em->flush();

        $data['delete_form'] = $this->createDeleteForm($data['entity']->getId())->createView();

        return $data;
    }


    /**
     * Finds and displays a Node entity.
     * @Template()
     */
    public function showBlockAction($id)
    {
        $data = array();

        $em = $this->getDoctrine()->getManager();

        $data['entity'] = $em->getRepository('NodeBundle:Node')->findOneBy(
            array(
                'id' => $id,
            )
        );

        if (!$data['entity']) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }


        return $data;
    }


    /**
     * @Template()
     * @param null $active
     * @return array
     */
    public function galleryMenuAction($active = null) {
        $data = [];

        $data['active'] = $active;

        $data['albums'] = $this->getDoctrine()->getRepository('NodeBundle:Node')
        ->findBy(
            array(
                'category' => 4
            ),
            array(
                'id' => 'desc'
            )
        );

        return $data;
    }


    /**
     * Displays a form to edit an existing Node entity.
     *@Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($id)
    {
        

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NodeBundle:Node')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NodeBundle:Node:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'photo_form' => $this->createPhotoForm(new NodeAttach())->createView()
        ));
    }

    /**
     * Creates a form to edit a Node entity.
     *
     * @param Node $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Node $entity)
    {
        $form = $this->createForm(new NodeType(), $entity, array(
            'action' => $this->generateUrl('node_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Node entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NodeBundle:Node')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Node entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('node_edit', array('id' => $id)));
        }

        return $this->render('NodeBundle:Node:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'photo_form' => $this->createPhotoForm(new NodeAttach())->createView()
        ));
    }

    /**
     * Deletes a Node entity.
     *@Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {
        

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('NodeBundle:Node')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Node entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('node'));
    }

    /**
     * Creates a form to delete a Node entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('node_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

    /**
     * @param NodeAttach $nodeAttach
     * @return \Symfony\Component\Form\Form
     */
    private function createPhotoForm(NodeAttach $nodeAttach)
    {
        $uploadForm = $this->createFormBuilder($nodeAttach)
            ->setAction($this->generateUrl('node_attach_load'))
            ->add ('photo','file')
            ->getForm();

        return $uploadForm;
    }

    /**
     * @param Request $request
     * @return Response
     * @Method("POST")
     */
    public function loadAction(Request $request) {
        //проверка доступа
        

        $photo = new NodeAttach();

        $uploadForm = $this->createPhotoForm($photo);

        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {
            $uploadForm->bind($request);



            if ($uploadForm->isValid()) {
                $em->persist($photo);
                $em->flush();
            }
        }
        if ($photo->getId()) {
            $data = $photo->getPhoto();
            $data['id'] = $photo->getId();
        } else {
            $data['error'] = true;
        }
        $data = json_encode($data);
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }



    /**
     * @param $id int
     * @return Response
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deletePhotoAction($id) {

        $photo = $this->getDoctrine()->getRepository('NodeBundle:NodeAttach')
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
     * @Template
     */
    public function blogSliderBlockAction () {
        $data = [];

        $category = $this->getDoctrine()->getRepository('NodeBundle:NodeCategory')
            ->findOneBy(
                array(
                    'slug' => 'blog'
                )
            );

        if ( ! $category) {
            throw $this->createNotFoundException('Категория не найдена');
        }
        $posts = $this->getDoctrine()->getRepository('NodeBundle:Node')
            ->findBy(array('category' => $category), array('id'=>'desc'));

        $removeKey = false;
        foreach ($posts as $key => $item) {

            $temp_array = [];

            if ($removeKey and $key == $removeKey) {
                continue;
            }

            $temp_array[] = $item;

            if (isset($posts[$key+1])) {
                $temp_array[] = $posts[$key+1];

                $removeKey = $key+1;
            }

            $data['posts'][] = $temp_array;
        }

        return $data;
    }
}
