<?php

namespace Core\NodeBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Core\UserBundle\Entity\User;

/**
 * @ORM\Table(name="Node")
 * @ORM\Entity()
 */
class Node
{

    /**
     * @var integer
     *
     * @ORM\Column(name="Node_Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="NodeCategory")
     * @ORM\JoinColumn(onDelete="CASCADE", referencedColumnName="Nc_Id", name="Node_Category", nullable=false)
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="Node_Views", type="integer", nullable=true)
     */
    private $views;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
     * @ORM\JoinColumn(onDelete="CASCADE", name="Node_Usr_Create", referencedColumnName="id", nullable=false)
     */
    private $userCreate;


    /**
     * @var string
     * @ORM\Column(name="Node_Title", type="string", length=255)
     * @Assert\Length(min="4", minMessage="Заголовок должен содержать не менее 4 символов")
     */
    private $title;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="Node_Date_Create")
     */
    private $dateCreated;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", name="Node_Date_Update")
     */
    private $dateUpdated;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="Node_Slug", length=225, unique=true)
     */
    private $slug;



    /**
     * @var string
     * @ORM\Column(name="Node_Main", type="text")
     */
    private $main;



    /**
     * @var string
     * @ORM\Column(name="Node_Short_Main", type="text", nullable=true)
     */
    private $short_main;



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
     * Set views
     *
     * @param integer $views
     * @return Node
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Node
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
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Node
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
     * @return Node
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
     * Set slug
     *
     * @param string $slug
     * @return Node
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
     * Set main
     *
     * @param string $main
     * @return Node
     */
    public function setMain($main)
    {
        $this->main = htmlspecialchars($main);

        return $this;
    }

    /**
     * Get main
     *
     * @return string 
     */
    public function getMain()
    {
        return htmlspecialchars_decode($this->main);
    }

    /**
     * Set short_main
     *
     * @param string $shortMain
     * @return Node
     */
    public function setShortMain($shortMain)
    {
        $this->short_main = $shortMain;

        return $this;
    }

    /**
     * Get short_main
     *
     * @return string 
     */
    public function getShortMain()
    {
        return $this->short_main;
    }

    /**
     * Set category
     *
     * @param NodeCategory $category
     * @return Node
     */
    public function setCategory(NodeCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Core\NodeBundle\Entity\NodeCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set userCreate
     *
     * @param \Core\UserBundle\Entity\User $userCreate
     * @return Node
     */
    public function setUserCreate(User $userCreate)
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

    public function getImg() {
        preg_match_all('~src="(.*?)"~', $this->getMain(), $img);

        if ($img = $img[1]) {
            return $img;
        }
        return false;
    }
}
