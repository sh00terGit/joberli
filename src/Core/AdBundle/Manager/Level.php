<?php

namespace Core\AdBundle\Manager;

use Core\CoreBundle\Services\Setting;
use Core\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class Level
{

    private $em;
    private $userStat;
    private $adStat;
    private $settings;
    private $property = [
          1 => [
              'days' => 7,
              'order_completed' => 10,
              'rating' => 4,
          ]  ,
          2 => [
              'days' => 21,
              'order_completed' => 30,
              'rating' => 4.5,
          ]  ,
          3 => [
              'days' => 21,
              'order_completed' => 50,
              'rating' => 4.7,
          ]  ,
        ];

    public $subAds = [
        0 => 2,
        1 => 3,
        2 => 4,
        3 => 4,
    ];



    public function __construct (ObjectManager $em, \Core\UserBundle\Manager\Stat $userStat, \Core\AdBundle\Manager\Stat $adStat, Setting $settings) {
        $this->em = $em;
        $this->userStat = $userStat;
        $this->adStat = $adStat;
        $this->settings = $settings;
    }


    /**
     * @param User $user
     * @return int
     */
    private function get (User $user) {
        $level = 0;
        $propertys = $this->property;
        $days = $this->userStat->getDaysOnSite($user);
        $rating = $this->adStat->userRating($user);
        $orderCompleted = $this->adStat->completedOrders($user);

        foreach ($propertys as $key => $item) {

            if ($item['days'] <= $days and $item['rating'] <= $rating and $item['order_completed'] <= $orderCompleted) {
                $level = $key;
                break;
            }

        }

        return $level;
    }


    public function update (User $user) {
        $em = $this->em;
        $level = $this->get($user);

        $user->setLevel($level);
        $em->persist($user);
        $em->flush();

        return $user;
    }
}