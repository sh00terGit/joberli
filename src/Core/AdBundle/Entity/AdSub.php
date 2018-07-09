<?php
namespace Core\AdBundle\Entity;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name = "AdSub")
 */
class AdSub
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer", name="Ads_Id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", name="Ads_Title", nullable=false)
     * @var string
     */
    private $title;

    /**
     * @var integer
     * @ORM\Column(name="Ads_Price", type="integer", nullable=false);
     */
    protected $price;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="Ads_Date_Create")
     */
    private $date;

    /**
     * @var integer
     * @ORM\Column(name="Ads_Execut_Days", type="integer", nullable=false)
     */
    protected $executDays = 3;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Core\AdBundle\Entity\Ad", inversedBy="subAd")
     * @ORM\JoinColumn(onDelete="CASCADE", name="Ads_Ad_Id", referencedColumnName="id", nullable=false)
     */
    private $ad;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }



    /**
     * Set executDays
     *
     * @param integer $executDays
     *
     * @return AdSub
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
     * Set ad
     *
     * @param \Core\AdBundle\Entity\Ad $ad
     *
     * @return AdSub
     */
    public function setAd(\Core\AdBundle\Entity\Ad $ad = null)
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
}
