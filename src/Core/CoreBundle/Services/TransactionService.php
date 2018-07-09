<?php
namespace Core\CoreBundle\Services;

use Core\CoreBundle\Entity\Transaction;
use Core\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class TransactionService {

    public $em;

    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }

    /**
     * @param User $user
     * @param $amount
     * @param $notification
     * @internal param $balance
     */
    public function add(User $user, $amount, $notification) {
        $transaction = new Transaction();

        $transaction->setUser($user);
        $transaction->setAmount($amount);
        $transaction->setBalance($user->getUserData()->getBalance());
        $transaction->setNotification($notification);

        $userBalance = $user->getUserData()->getBalance()+$amount;

        $user->setBalance($userBalance);

        $this->em->persist($transaction);
        $this->em->persist($user);
        $this->em->flush();
    }
}