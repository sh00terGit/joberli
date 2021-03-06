<?php

namespace Core\AdBundle\Entity;

use DateInterval;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineExtensions\Taggable\Taggable;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Core\UserBundle\Entity\User;

/**
 * @ORM\Table(name="AdRating")
 * @ORM\Entity(repositoryClass="AdRatingRepository")
 */
class AdRating
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
     * @var $rate integer
     *
     * @ORM\Column(type="integer", name="Adr_Rate", nullable=false)
     */
    private $rate = 1;


    /**
     * @var int
     * @ORM\Column(name="Adr_Comment", type="text", nullable=false)
     */
    protected $comment;

    /**
     * @var int
     * @ORM\Column(name="Adr_Type", type="integer", nullable=false)
     */
    protected $type = 0;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Core\AdBundle\Entity\Ad", inversedBy="rating", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE", name="Adr_Ad_Id", referencedColumnName="id", nullable=false)
     */
    protected $ad;


    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn( name="Adr_Usr_Id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Core\AdBundle\Entity\AdOrder")
     * @ORM\JoinColumn(onDelete="CASCADE", name="Adr_Order", referencedColumnName="id", nullable=false)
     */
    protected $order;


    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="Ado_Date_Create")
     */
    private $dateCreated;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", name="Ado_Date_Update")
     */
    private $dateUpdated;



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
     * Set rate
     *
     * @param $rate
     *
     * @return AdRating
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return AdRating
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return AdRating
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return AdRating
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
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     *
     * @return AdRating
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set ad
     *
     * @param \Core\AdBundle\Entity\Ad $ad
     *
     * @return AdRating
     */
    public function setAd(\Core\AdBundle\Entity\Ad $ad)
    {
        $this->ad = $ad;

        return $this;
    }

    /**
     * Get ad
     *
     * @return \Core\AdBundle\Entity\Ad
     */
    public function getAd()
    {
        return $this->ad;
    }

    /**
     * Set order
     *
     * @param \Core\AdBundle\Entity\AdOrder $order
     *
     * @return AdRating
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

    /**
     * @param User $user
     * @return AdRating
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
