<?php

namespace Core\CoreBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;

class Setting
{
    public function __construct(ObjectManager $em) {
        $this->em = $em;

        $settings = $em->getRepository('CoreBundle:Settings')->findAll();
        foreach ($settings as $item) {
            $this->settings[$item->getLink()] = $item;
        }

    }

    protected $em;
    protected $settings;


    public function update($link, $value) {

        if ( ! isset($this->settings[$link])) {
            return false;
        }

        $setting = $this->settings[$link];

        $setting->setValue($value);
        $this->em->persist($setting);
        $this->em->flush();
    }


    public function get($link) {

        if ( ! isset($this->settings[$link])) {
            return false;
        }

        return $this->settings[$link]->getValue();
    }

}