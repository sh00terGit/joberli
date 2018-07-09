<?php

namespace Core\MetronicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller {

    /**
     * @Template()
     */
    public function indexAction() {
        $data = array();

        $data['page']['title'] = 'dddd';

        return $data;
    }
}