<?php

namespace Core\UserBundle\Manager;

use Core\UserBundle\Entity\User;
use DateTime;
use Doctrine\Common\Persistence\ObjectManager;

class Stat {

    private $em;

    /**
     * @param ObjectManager $em
     */
    public function __construct (ObjectManager $em) {
        $this->em = $em;
    }


    /**
     * @param $user User
     * @return mixed
     */
    public function getDaysOnSite ($user) {
        $days = date_diff($user->getDateCreated(), new DateTime())->days;
        return $days;
    }
}