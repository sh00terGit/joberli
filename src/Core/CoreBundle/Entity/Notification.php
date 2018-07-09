<?php
namespace Core\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity
 * @ORM\Table(name="Notification")
 */
class Notification {
    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\Column(name="Not_Id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="Not_Title", type="string")
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(name="Not_Main", type="text")
     */
    protected $main;

    /**
     * @var integer
     * @ORM\Column(name="Not_Attr", type="integer")
     */
    protected $attr;

    /**
     * @var boolean
     * @ORM\Column(name="Not_New", type="boolean")
     */
    protected $new;

    /**
     * @var \DateTime $dateCreated
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="Not_Date_Create")
     */
    protected $dateCreated;

    /**
     * @var string
     * @ORM\Column(name="Not_Type", type="string")
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="Not_Usr_Id", onDelete="CASCADE", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @var array()
     * @ORM\Column(name="Not_Group", type="array", nullable=true)
     */
    protected $group;


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
     * Set title
     *
     * @param string $title
     *
     * @return Notification
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set main
     *
     * @param string $main
     *
     * @return Notification
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return string
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Notification
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
     * Set type
     *
     * @param string $type
     *
     * @return Notification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set group
     *
     * @param string $group
     *
     * @return Notification
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set user
     *
     * @param \Core\UserBundle\Entity\User $user
     *
     * @return Notification
     */
    public function setUser(\Core\UserBundle\Entity\User $user = null)
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
     * @return int
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * @param int $attr
     */
    public function setAttr($attr)
    {
        $this->attr = $attr;
    }

    /**
     * @return boolean
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * @param boolean $new
     */
    public function setNew($new)
    {
        $this->new = $new;
    }
}
