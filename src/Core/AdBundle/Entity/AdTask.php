<?php

namespace Core\AdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use DoctrineExtensions\Taggable\Taggable;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Core\UserBundle\Entity\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="AdTask")
 */
class AdTask
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
     * @ORM\JoinColumn(onDelete="CASCADE", name="Adt_User", referencedColumnName="id", nullable=false)
     */
    private $user;


    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Core\AdBundle\Entity\AdOrder", inversedBy="task", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE", name="Adt_Order", referencedColumnName="id", nullable=false)
     */
    private $order;

    /**
     * @var \DateTime $startDate
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="Adt_Date_Start")
     */
    private $startDate;

    /**
     * @var \DateTime $startDate
     *
     * @ORM\Column(type="datetime", name="Adt_Date_End")
     */
    private $endDate;

    /**
     * @var integer
     * @ORM\Column(name="Adt_Execut_Days", type="integer", nullable=false)
     */
    protected $executDays = 3;

    /**
     * @var integer
     * @ORM\Column(name="Adt_Priority", type="boolean", nullable=true)
     */
    protected $priority = false;

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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return AdTask
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return AdTask
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set executDays
     *
     * @param integer $executDays
     *
     * @return AdTask
     */
    public function setExecutDays($executDays)
    {
        $this->executDays = $executDays;

        return $this;
    }

    /**
     * Get executDays
     *
     * @return integer
     */
    public function getExecutDays()
    {
        return $this->executDays;
    }

    /**
     * Set priority
     *
     * @param boolean $priority
     *
     * @return AdTask
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return boolean
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set user
     *
     * @param \Core\UserBundle\Entity\User $user
     *
     * @return AdTask
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
     * Set order
     *
     * @param \Core\AdBundle\Entity\AdOrder $order
     *
     * @return AdTask
     */
    public function setOrder(\Core\AdBundle\Entity\AdOrder $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Core\AdBundle\Entity\AdOrder
     */
    public function getOrder()
    {
        return $this->order;
    }
}
