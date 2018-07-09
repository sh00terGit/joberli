<?php
namespace Core\NodeBundle\Entity;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name = "NodeAttach")
 * @FileStore\Uploadable
 */
class NodeAttach
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer", name="Na_Id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="string", name="Na_Title", nullable=true)
     * @var string
     */
    private $title;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="Na_Date_Create")
     */
    private $date;


    /**
     * @ORM\Column(type="array", name="Na_Photo")
     * @Assert\Image( maxSize="20M", minHeight="200", minWidth="200")
     * @FileStore\UploadableField(mapping="photo")
     **/
    private $photo;


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
     * @return NodeAttach
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
     * Set date
     *
     * @param \DateTime $date
     * @return NodeAttach
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
     * @return NodeAttach
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


    public function getFileName() {
        return 'natt' . $this->getId() . "_" . time();
    }
}
