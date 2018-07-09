<?php

namespace Core\AdBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SaleController extends Controller
{
    /**
     * @return array
     * @Template
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {
        $data = [];
        $user = $this->getUser();
        $data['page']['userMenu'] = true;
        $data['page']['title'] = 'Продажи';

        $data['sales'] = $this->getDoctrine()->getRepository('AdBundle:AdOrder')
            ->findBy(
                array(
                    'venditore' => $user
                ),
                array(
                    'dateCreated' => 'DESC'
                )
            );

        return $data;
    }

    /**
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function statAction() {
        $data = [];

        $data['page']['title'] = 'Статистика';
        return $data;
    }

    /**
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function servicesAction() {
        $data = [];
        $user = $this->getUser();

        $data['page']['title'] = 'Мои услуги';

        $ads = $this->getDoctrine()->getRepository('AdBundle:Ad')
            ->findBy(
                array(
                    'userCreate' => $user
                ),
                array(
                    'dateCreated' => 'DESC'
                )
            );

        $data['ads'] = $ads;

        return $data;
    }

    /**
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function financesAction() {
        $data = [];

        $data['page']['title'] = 'Финансы';
        return $data;
    }
}
