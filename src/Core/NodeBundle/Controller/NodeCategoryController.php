<?php

namespace Core\NodeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\NodeBundle\Entity\NodeCategory;
use Core\NodeBundle\Form\NodeCategoryType;

/**
 * NodeCategory controller.
 *
 */
class NodeCategoryController extends Controller
{

    /**
     * Lists all NodeCategory entities.
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function managerAction()
    {
        //Проверка прав доступа
        

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('NodeBundle:NodeCategory')->findAll();

        return $this->render('NodeBundle:NodeCategory:manager.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new NodeCategory entity.
     *
     */
    public function createAction(Request $request)
    {
        //Проверка прав доступа
        

        $user = $this->getUser();
        $entity = new NodeCategory();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setUserCreate($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('node_cat_manager'));
        }

        return $this->render('NodeBundle:NodeCategory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a NodeCategory entity.
     *
     * @param NodeCategory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(NodeCategory $entity)
    {
        $form = $this->createForm(new NodeCategoryType(), $entity, array(
            'action' => $this->generateUrl('node_cat_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new NodeCategory entity.
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newAction()
    {
        $entity = new NodeCategory();
        $form   = $this->createCreateForm($entity);

        return $this->render('NodeBundle:NodeCategory:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a NodeCategory entity.
     * @Template()
     *
     */
    public function showAction($slug)
    {
        $data = [];
        //Проверка прав доступа
        $em = $this->getDoctrine()->getManager();

        $data['entity'] = $em->getRepository('NodeBundle:NodeCategory')->findOneBy(
            array(
                'slug' => $slug
            )
        );

        if (!$data['entity']) {
            throw $this->createNotFoundException('Unable to find NodeCategory entity.');
        }

        $data['last_post'] = $this->getDoctrine()->getRepository('NodeBundle:Node')
            ->findBy(array('category' => $data['entity']), array('id'=>'desc'));


        $data['delete_form'] = $this->createDeleteForm($data['entity']->getId())->createView();

        return $data;
    }

    /**
     * Displays a form to edit an existing NodeCategory entity.
     *@Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($id)
    {
        //Проверка прав доступа
        

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NodeBundle:NodeCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NodeCategory entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NodeBundle:NodeCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a NodeCategory entity.
    *
    * @param NodeCategory $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(NodeCategory $entity)
    {
        $form = $this->createForm(new NodeCategoryType(), $entity, array(
            'action' => $this->generateUrl('node_cat_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing NodeCategory entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NodeBundle:NodeCategory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NodeCategory entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('node_cat_edit', array('id' => $id)));
        }

        return $this->render('NodeBundle:NodeCategory:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a NodeCategory entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('NodeBundle:NodeCategory')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find NodeCategory entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('node_cat'));
    }

    /**
     * Creates a form to delete a NodeCategory entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('node_cat_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
