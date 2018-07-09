<?php

namespace Core\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{

    /**
     * @Template
     */
    public function indexAction()
    {
        $data = array();

        $data['page']['title'] = 'Главная страница';
        $data['page']['title_help'] = 'Добропожаловать на Joberli';

        return $data;
    }

}
