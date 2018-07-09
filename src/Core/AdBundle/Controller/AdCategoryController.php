<?php

namespace Core\AdBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Core\AdBundle\Entity\AdCategory;
use Core\AdBundle\Form\AdCategoryType;
use Symfony\Component\HttpFoundation\Response;

/**
 * AdCategory controller.
 *
 */
class AdCategoryController extends Controller
{

    /**
     * Lists all AdCategory entities.
     *
     */
    public function indexAction()
    {
        $data = array();
        $data['page']['title'] = 'Менеджер упарвления категориями';

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdBundle:AdCategory')->findAll();

        return $this->render('AdBundle:AdCategory:index.html.twig', array(
            'entities' => $entities,
        ));
    }


    /**
     * Creates a new AdCategory entity.
     * @Security("has_role( 'ROLE_SUPER_ADMIN')")
     */
    public function createAction(Request $request)
    {

        $data = array();
        $categoryRequest = $request->request->get('category');

        $category = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
            ->find($categoryRequest);

        $data['entity'] = new AdCategory();
        $data['form'] = $this->createCreateForm($data['entity']);
        $data['form']->handleRequest($request);

        if ($data['form']->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($category) {
                $data['entity']->setParent($category);
            }

            $em->persist($data['entity']);
            $em->flush();

            return $this->redirect($this->generateUrl('adcategory_show', array('slug' => $data['entity']->getPath()['path'], 'child_slug' => $data['entity']->getPath()['child'])));
        }

        $data['form'] = $data['form']->createView();
        $data['category'] = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
            ->findBy(
                array(
                    'parent' => null
                )
            );

        return $this->render('AdBundle:AdCategory:new.html.twig', $data);
    }

    /**
     * Creates a form to create a AdCategory entity.
     *
     * @param AdCategory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AdCategory $entity)
    {
        $form = $this->createForm(new AdCategoryType(), $entity, array(
            'action' => $this->generateUrl('adcategory_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new AdCategory entity.
     * @Template
     * @param Request $request
     * @return array
     * @Security("has_role( 'ROLE_SUPER_ADMIN')")
     */
    public function newAction(Request $request)
    {
        $data = array();

        $data['category'] = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
            ->findBy(
                array(
                    'parent' => null
                )
            );

        $data['entity'] = new AdCategory();
        $form   = $this->createCreateForm($data['entity']);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $categoryRequest = $request->get('category');
            $category = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
                ->find($categoryRequest);

            $em = $this->getDoctrine()->getManager();

            if ($category) {
                $data['entity']->setParent($category);
            }

            $em->persist($data['entity']);
            $em->flush();

            return $this->redirect($this->generateUrl('adcategory'));
        }

        $data['form'] = $form->createView();

        return $data;
    }


    /**
     * Finds and displays a AdCategory entity.
     * @Template
     */
    public function showAction(Request $request, $slug, $child_slug = null)
    {

        $em = $this->getDoctrine()->getManager();
        $data = array();
        $where = array(
            'slug' => $slug
        );

        if ($child_slug) {
            $parent = $em->getRepository('AdBundle:AdCategory')->findOneBy($where);

            if (!$parent) {
                throw $this->createNotFoundException('Unable to find AdCategory entity.');
            }

            $where['parent'] = $parent->getId();
            $where['slug'] = $child_slug;
        }


        $data['entity'] = $em->getRepository('AdBundle:AdCategory')->findOneBy($where);

        if (!$data['entity']) {
            throw $this->createNotFoundException('Unable to find AdCategory entity.');
        }

        $data['page']['title'] = $data['entity']->getTitle();


        $data['delete_form'] = $this->createDeleteForm($data['entity']->getId())->createView();

        return $data;
    }

    /**
     * Displays a form to edit an existing AdCategory entity.
     * @Security("has_role( 'ROLE_SUPER_ADMIN')")
     */
    public function editAction($id)
    {

        $data = array();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdBundle:AdCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdCategory entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('AdBundle:AdCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a AdCategory entity.
    *
    * @param AdCategory $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(AdCategory $entity)
    {
        $form = $this->createForm(new AdCategoryType(), $entity, array(
            'action' => $this->generateUrl('adcategory_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing AdCategory entity.
     * @Security("has_role( 'ROLE_SUPER_ADMIN')")
     */
    public function updateAction(Request $request, $id)
    {

        $data = array();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdBundle:AdCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AdCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('adcategory_edit', array('id' => $id)));
        }

        return $this->render('AdBundle:AdCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a AdCategory entity.
     * @Security("has_role( 'ROLE_SUPER_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {

        $data = array();

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdBundle:AdCategory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AdCategory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('adcategory'));
    }

    /**
     * Creates a form to delete a AdCategory entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('adcategory_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @Template()
     */
    public function menuAction() {
        $data = [];

        $data['categories'] = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
            ->findBy(
                array(
                    'parent' => null,
                    'public' => true
                ),
                array(
                    'sort' => 'ASC'
                )
            )
        ;

        $data['categories_others'] = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
            ->findBy(
                array(
                    'parent' => null,
                    'public' => false
                ),
                array(
                    'sort' => 'ASC'
                )
            )
        ;
        return $data;
    }

    /**
     * @return array
     * @Template()
     * @Security("has_role( 'ROLE_SUPER_ADMIN')")
     */
    public function levelManagerAction(Request $request) {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $data['page']['title'] = 'Менеджер управления категориями';


        $data['categories'] = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
            ->findBy(
                array(
                    'parent' => null,
                    'public' => true
                ),
                array(
                    'sort' => 'ASC'
                )
            )
        ;

        $data['categories_other'] = $this->getDoctrine()->getRepository('AdBundle:AdCategory')
            ->findBy(
                array(
                    'parent' => null,
                    'public' => false
                ),
                array(
                    'sort' => 'ASC'
                )
            )
        ;

        if (!$list = $request->get('list')) {
            return $data;
        }

        foreach ($list as $key => $item) {
            $parent = $em->getRepository('AdBundle:AdCategory')->find($item['id']);

            if ($parent) {
                $parent->setSort($key);
                $parent->setPublic(true);
                $em->persist($parent);
            }


            if (!isset($item['children'])) {
                continue;
            }

            foreach ($item['children'] as $child_key => $child_item) {
                $category = $em->getRepository('AdBundle:AdCategory')->find($child_item['id']);
                $category->setSort($child_key);

                if ($parent) {
                    $category->setPublic(true);
                } else {
                    $category->setPublic(false);
                }

                $em->persist($category);
            }

        }

        $em->flush();

        $response = new JsonResponse();
        $response->setData($data);
        return $response;
    }
}
