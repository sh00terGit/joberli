<?php

namespace Core\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SettingController extends Controller
{
    /**
     * @return array
     * @Template()
     * @Security("has_role( 'ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        $data = [];

        $data['page']['title'] = 'Настройки сайта';
        $data['settings'] = $this->getDoctrine()->getRepository('CoreBundle:Settings')->findAll();
        $setting = $this->get('site.setting');

        if ($request->isMethod('POST')) {
            $formData = $request->request->all();

            foreach ($formData as $key=>$item) {
                $setting->update($key, $item);
            }

        }

        return $data;
    }
}
