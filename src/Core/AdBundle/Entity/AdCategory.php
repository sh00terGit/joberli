<?php

namespace Core\AdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="Ad_Category")
 * @ORM\Entity()
 * @FileStore\Uploadable
 */
class AdCategory {

    public function __construct()
    {
        $this->child = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="Core\AdBundle\Entity\AdCategory", mappedBy="parent")
     * @ORM\OrderBy({"sort" = "ASC"})
     */
    protected $child;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="Adc_Id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="Adc_Title", type="string", nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="array", name="Adc_Cover", nullable=true)
     * @Assert\Image( maxSize="20M", minHeight="200", minWidth="1170")
     * @FileStore\UploadableField(mapping="photo")
     **/
    private $cover;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="Adc_Slug", length=225, unique=true)
     */
    private $slug;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Core\AdBundle\Entity\AdCategory", inversedBy="child")
     * @ORM\JoinColumn(referencedColumnName="Adc_Id", onDelete="CASCADE", fieldName="Adc_Parent", nullable=true)
     */
    private $parent;

    /**
     * @var integer
     *
     * @ORM\Column(name="Adc_Sort", type="integer", nullable=true)
     */
    protected $sort;

    /**
     * @var boolean
     *
     * @ORM\Column(name="Adc_Public", type="boolean", nullable=true)
     */
    protected $public;


    private $path;

    /**
     * @var string
     * @ORM\Column(name="Adc_Description", type="text", nullable=true)
     */
    private $description;

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
     * @return AdCategory
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
     * @return AdCategory
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
     * Set description
     *
     * @param string $description
     * @return AdCategory
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
     * Set parent
     *
     * @param \Core\AdBundle\Entity\AdCategory $parent
     * @return AdCategory
     */
    public function setParent(AdCategory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Core\AdBundle\Entity\AdCategory 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        $data = array();
        $data['path'] =  $this->slug;
        $data['child'] = null;
        if ($this->getParent()) {
            $data['path'] = $this->getParent()->getSlug();
            $data['child'] = $this->slug;
        }

        return $data;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Set cover
     *
     * @param array $cover
     * @return AdCategory
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return array 
     */
    public function getCover()
    {
        return $this->cover;
    }

    public function getFileName() {
        return 'adcat' . $this->getId() . "_" . time();
    }

    /**
     * @return mixed
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @param mixed $child
     */
    public function setChild($child)
    {
        $this->child = $child;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return boolean
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }
}
