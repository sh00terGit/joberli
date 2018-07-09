<?php

namespace Core\AdBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdTaskController extends Controller
{
    /**
     * @return array
     * @Security("has_role('ROLE_USER')")
     * @Template()
     */
    public function indexAction()
    {
        $data = [];
        $data['page']['userMenu'] = true;
        $data['page']['title'] = 'Список задач';
        $user = $this->getUser();

        $data['task'] = $this->getDoctrine()->getRepository('AdBundle:AdTask')
            ->findBy(
                array(
                    'user' => $user
                )
            );

        return $data;
    }
}
