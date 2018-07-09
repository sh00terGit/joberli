<?php
namespace Core\AdBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Core\AdBundle\Entity\Ad;
use Core\AdBundle\Entity\AdTask;
use Core\AdBundle\Entity\AdOrder;
use Core\UserBundle\Entity\User;

class TaskManager
{
    public $em;

    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }

    public function addTask(User $user, AdOrder $adOrder)
    {
        $adTask = new AdTask();

        $finDay = new \DateTime('NOW');
        $finDay->modify('+' . $adOrder->getExecutDaysAmount() . ' day');

        $adTask->setUser($user);
        $adTask->setOrder($adOrder);
        $adTask->setEndDate($finDay);
        $adTask->setPriority(false);

        $this->em->persist($adTask);
        $this->em->flush();

    }
}