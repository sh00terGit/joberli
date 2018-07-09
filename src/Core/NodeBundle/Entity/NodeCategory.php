<?php

namespace Core\NodeBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Core\UserBundle\Entity\User;

/**
 * @ORM\Table(name="NodeCategory")
 * @ORM\Entity()
 */
class NodeCategory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Nc_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="Nc_Title", type="string", length=255)
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="Nc_Slug", length=225, unique=true)
     */
    private $slug;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
     * @ORM\JoinColumn(onDelete="CASCADE", referencedColumnName="id", name="Nc_Usr_Create", nullable=false)
     */
    private $userCreate;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="Nc_Date_Create")
     */
    private $dateCreated;


    /**
     * @var string
     * @ORM\Column(name="Nc_Main", type="text", nullable=true)
     */
    private $main;

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
     * @return NodeCategory
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
     * Set slug
     *
     * @param string $slug
     * @return NodeCategory
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return NodeCategory
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
     * Set main
     *
     * @param string $main
     * @return NodeCategory
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
     * Set userCreate
     *
     * @param \Core\UserBundle\Entity\User $userCreate
     * @return NodeCategory
     */
    public function setUserCreate(\Core\UserBundle\Entity\User $userCreate)
    {
        $this->userCreate = $userCreate;

        return $this;
    }

    /**
     * Get userCreate
     *
     * @return \Core\UserBundle\Entity\User 
     */
    public function getUserCreate()
    {
        return $this->userCreate;
    }
}
