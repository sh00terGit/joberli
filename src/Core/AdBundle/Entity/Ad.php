<?php

namespace Core\AdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use DoctrineExtensions\Taggable\Taggable;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Core\UserBundle\Entity\User;

/**
 * @ORM\Table(name="Ad")
 * @ORM\Entity(repositoryClass="AdRepository")
 */
class Ad  implements Taggable
{
    public function __construct () {
        $this->userFavorite = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->subAd = new ArrayCollection();
        $this->rating = new ArrayCollection();
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="Ad_Price", type="integer", nullable=false)
     */
    protected $price = 7;

    /**
     * @var int
     * @ORM\Column(name="Ad_Status", type="integer", nullable=false)
     */
    protected $status = 0;

    /**
     * @ORM\ManyToMany(targetEntity="Core\UserBundle\Entity\User", inversedBy="adFavorite", cascade={"persist"}, indexBy="id")
     * @ORM\JoinTable(name="AdFavorite",
     * joinColumns={@ORM\JoinColumn(name="Ad_Id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="User_Id", referencedColumnName="id")}
     * )
     */
    protected $userFavorite;

    /**
     * @var integer
     *
     * @ORM\Column(name="Ad_Views", type="integer", nullable=true)
     */
    private $views;

    /**
     * @ORM\OneToMany(targetEntity="Core\AdBundle\Entity\AdSub", mappedBy="ad", cascade={"persist"})
     */
    protected $subAd;

    /**
     * @ORM\OneToMany(targetEntity="Core\AdBundle\Entity\AdRating", mappedBy="ad", cascade={"persist"})
     */
    protected $rating;


    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User")
     * @ORM\JoinColumn(onDelete="CASCADE", name="Ad_Usr_Create", referencedColumnName="id", nullable=false)
     */
    private $userCreate;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Core\AdBundle\Entity\AdCategory")
     * @ORM\JoinColumn(onDelete="CASCADE", name="Ad_Category", referencedColumnName="Adc_Id", nullable=false)
     */
    private $category;

    /**
     * @var integer
     * @ORM\Column(name="Usd_Execut_Days", type="integer", nullable=false)
     */
    protected $executDays = 3;


    /**
     * @var string
     * @ORM\Column(name="Ad_Title", type="string", length=255)
     */
    private $title;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="Ad_Date_Create")
     */
    private $dateCreated;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", name="Ad_Date_Update")
     */
    private $dateUpdated;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="Ad_Slug", length=225, unique=true)
     */
    private $slug;



    /**
     * @var string
     * @ORM\Column(name="Ad_Description", type="text")
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="Ad_Instruction", type="text")
     */
    private $instruction;

    /**
     * @ORM\OneToMany(targetEntity="Core\AdBundle\Entity\AdAttach", mappedBy="ad", cascade={"persist"})
     */
    private $photos;



    private $tags;



    public function getTags()
    {
        $this->tags = $this->tags ?: new ArrayCollection();

        return $this->tags;
    }


    public function getTaggableType()
    {
        return 'Ad';
    }

    public function getTaggableId()
    {
        return $this->getId();
    }

    // We added this to be able to save tags from a form
    public function setTags($tags)
    {
        $this->tags = is_array($tags) ? new ArrayCollection($tags) : $tags;
    }



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
     * @return Ad
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
     * Set description
     *
     * @param string $description
     * @return Ad
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set userCreate
     *
     * @param \Core\UserBundle\Entity\User $userCreate
     * @return Ad
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

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }


    /**
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param \DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @param \DateTime $dateUpdated
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
    }

    /**
     * Set category
     *
     * @param \Core\AdBundle\Entity\AdCategory $category
     * @return Ad
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer 
     */
    public function getCategory()
    {
        return $this->category;
    }

 

    /**
     * @return int
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews($views)
    {
        $this->views = $views;
    }

    /**
     * Add userFavorite
     *
     * @param \Core\UserBundle\Entity\User $userFavorite
     *
     * @return Ad
     */
    public function addUserFavorite(\Core\UserBundle\Entity\User $userFavorite)
    {
        $this->userFavorite[] = $userFavorite;

        return $this;
    }

    /**
     * Remove userFavorite
     *
     * @param \Core\UserBundle\Entity\User $userFavorite
     */
    public function removeUserFavorite(\Core\UserBundle\Entity\User $userFavorite)
    {
        $this->userFavorite->removeElement($userFavorite);
    }

    /**
     * @param User $user
     * @return bool|int|mixed|string
     */
    public function getIsUserFavorite(\Core\UserBundle\Entity\User $user)
    {
        if ($this->getUserFavorite()->indexOf($user)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get userFavorite
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserFavorite()
    {
        return $this->userFavorite;
    }


    /**
     * @return int
     */
    public function getExecutDays()
    {
        return $this->executDays;
    }

    /**
     * @param int $executDays
     */
    public function setExecutDays($executDays)
    {
        $this->executDays = $executDays;
    }

    /**
     * Add photo
     *
     * @param \Core\AdBundle\Entity\AdAttach $photo
     *
     * @return Ad
     */
    public function addPhoto($photo)
    {
        if (method_exists($photo, 'getTitle')) {
            return null;
        }

        $newAttach = new AdAttach();
        $newAttach->setAd($this);
        $newAttach->setPhoto($photo);
        $this->photos[] = $newAttach;

        return $this;
    }

    /**
     * Add photo
     *
     * @param \Core\AdBundle\Entity\AdAttach $photo
     *
     * @return Ad
     */
    public function setPhotos($photo)
    {
        $newAttach = new AdAttach();
        $newAttach->setAd($this);
        $newAttach->setPhoto($photo);

        $this->photos[] = $newAttach;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \Core\AdBundle\Entity\AdAttach $photo
     */
    public function removePhoto(\Core\AdBundle\Entity\AdAttach $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCover()
    {
        if ($cover = $this->getPhotos()->first()) {
            return $cover->getPhoto()['path'];
        }

        return 'bundles/core/img/no-image.jpg';
    }

    /**
     * @return string
     */
    public function getInstruction()
    {
        return $this->instruction;
    }

    /**
     * @param string $instruction
     */
    public function setInstruction($instruction)
    {
        $this->instruction = $instruction;
    }

    /**
     * Add subAd
     *
     * @param \Core\AdBundle\Entity\AdSub $subAd
     *
     * @return Ad
     */
    public function addSubAd(\Core\AdBundle\Entity\AdSub $subAd)
    {
        $this->subAd[] = $subAd;

        return $this;
    }

    /**
     * Remove subAd
     *
     * @param \Core\AdBundle\Entity\AdSub $subAd
     */
    public function removeSubAd(\Core\AdBundle\Entity\AdSub $subAd)
    {
        $this->subAd->removeElement($subAd);
    }

    /**
     * Get subAd
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubAd()
    {
        return $this->subAd;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Ad
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return float|int
     */
    public function getRatingStars()
    {
        $stars = 0;

        if ( ! $this->getRating()) {
            return $stars;
        }

        $rating = $this->getRating();

        if (! $rating or count($rating) < 1) {
            return $stars;
        }

        $count = count($rating);
        foreach ($rating as $item) {
            $stars+= $item->getRate();
        }

        $stars = round(($stars/$count));

        return $stars;
    }
}
