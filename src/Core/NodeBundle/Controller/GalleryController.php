<?php

namespace Core\NodeBundle\Controller;


use Core\NodeBundle\Entity\NodeAttach;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
/**
 * Node controller.
 *
 */
class GalleryController extends Controller
{
    /**
     * @return array()
     * @Template()
     */
    public function formAction()
    {
        $nodeAttach = new NodeAttach();
        $data = array();

        $data['images'] = $this->getDoctrine()->getRepository('NodeBundle:NodeAttach')
            ->findBy(array(), array('id'=>'desc'), 15);

        $data['form'] = $this->createFormBuilder($nodeAttach)
            ->setAction($this->generateUrl('node_attach_load'))
            ->add ('photo','file')
            ->getForm()
            ->createView();

        return $data;
    }

    /**
     * @param Request $request
     * @return Response
     * @Method("POST")
     */
    public function loadAction(Request $request) {
        //проверка доступа
        

        $photo = new NodeAttach();
        $data = array();

        $uploadForm = $this->createFormBuilder($photo)
            ->setAction($this->generateUrl('node_attach_load'))
            ->add ('photo','file')
            ->getForm();

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
}
