<?php

namespace Core\AdBundle\Manager;

use Core\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class Stat {

    private $em;

    public function __construct (ObjectManager $em) {
        $this->em = $em;
    }

    /**
     * @param $user User
     * @return float|int
     */
    public function userRating ($user) {
        $em = $this->em;

        $data = $em->getRepository('AdBundle:AdRating')->createQueryBuilder('r')
            ->select('SUM(r.rate), COUNT(r.id)')
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ( ! $data or ! $data[1]) {
            return 0;
        }

        $rating = round(($data[1]/$data[2]), 1);

        return $rating;
    }

    /**
     * @param $user User
     * @return int
     */
    public function completedOrders ($user) {
        $em = $this->em;

        $data = $em->getRepository('AdBundle:AdOrder')->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where('o.venditore = :user')
            ->andWhere('o.status = :status')
            ->setParameters(
                array(
                    'user' => $user,
                    'status' => 2
                )
            )
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ( ! $data) {
            return 0;
        }

        $orders = $data[1];

        return $orders;
    }

    /**
     * @param $user User
     * @return bool
     */
    public function updateRatingUser (User $user) {
        $em = $this->em;

        $rating = $this->userRating($user);

        $user->setRating($rating);
        $em->persist($user);
        $em->flush();

        return true;
    }
}