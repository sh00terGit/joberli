<?php
namespace Core\AdBundle\Entity;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name = "AdAttach")
 * @FileStore\Uploadable
 */
class AdAttach
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer", name="Ada_Id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", name="Ada_Title", nullable=true)
     * @var string
     */
    private $title;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="Ada_Date_Create")
     */
    private $date;


    /**
     * @ORM\Column(type="array", name="Ada_Photo")
     * @Assert\Image( maxSize="20M", minHeight="200", minWidth="200")
     * @FileStore\UploadableField(mapping="photo")
     **/
    private $photo;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Core\AdBundle\Entity\Ad", inversedBy="photos", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE", name="Ada_Ad_Id", referencedColumnName="id", nullable=true)
     */
    private $ad;


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
     * @return AdAttach
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

    public function getFileName() {
        return 'adatt' . $this->getId() . "_" . time();
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return AdAttach
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set photo
     *
     * @param array $photo
     * @return AdAttach
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return array 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set ad
     *
     * @param \Core\AdBundle\Entity\Ad $ad
     * @return AdAttach
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

}
