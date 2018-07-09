<?php

namespace Core\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity()
 * @ORM\Table(name="Transaction")
 */
class Transaction {
    /**
     * @var int
     * @ORM\Column(name="Trn_Id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string
     * @ORM\Column(name="Trn_Type", type="string")
     */
    protected $type = 'balance_replenish';


    /**
     * @var string
     * @ORM\Column(name="Trn_Note", type="text", nullable=true)
     */
    protected $note;

    /**
     * @var boolean
     * @ORM\Column(name="Trn_Status", type="boolean")
     */
    protected $status = true;

    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="Trn_Usr_Id", onDelete="CASCADE", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Core\CoreBundle\Entity\Notification")
     * @ORM\JoinColumn(name="Trn_Not_Id", onDelete="CASCADE", referencedColumnName="Not_Id", nullable=true)
     */
    protected $notification;

    /**
     * @var string
     *
     * @ORM\Column(name="Trn_Amount", type="string", nullable=true)
     */
    protected $amount;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="Trn_Date_Create")
     */
    private $dateCreated;

    /**
     * @var integer
     * @ORM\Column(name="Trn_Balance", type="integer", nullable=false)
     */
    protected $balance = 0;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Transaction
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set balance
     *
     * @param integer $balance
     *
     * @return Transaction
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return integer
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Set user
     *
     * @param \Core\UserBundle\Entity\User $user
     *
     * @return Transaction
     */
    public function setUser(\Core\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Core\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set notification
     *
     * @param \Core\CoreBundle\Entity\Notification $notification
     *
     * @return Transaction
     */
    public function setNotification(\Core\CoreBundle\Entity\Notification $notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \Core\CoreBundle\Entity\Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @return boolean
     */
    public function isStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }
}
