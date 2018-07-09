<?php

namespace Core\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Iphp\FileStoreBundle\Mapping\Annotation as FileStore;


/**
 * @ORM\Entity(repositoryClass="UserDataRepository", )
 * @ORM\Table(name="UserData")
 * @FileStore\Uploadable
 */
class UserData {
    /**
     * @var int
     * @ORM\Column(name="Usd_Id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Core\UserBundle\Entity\User", inversedBy="userData")
     * @ORM\JoinColumn(name="Usd_Usr_Id", onDelete="CASCADE", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="Usd_Purse", type="string", nullable=true)
     */
    protected $purse;


    /**
     * @var integer
     * @ORM\Column(name="Usd_About", type="text", nullable=true)
     */
    protected $about;

    /**
     * @var integer
     * @ORM\Column(name="Usd_Balance", type="integer", nullable=false)
     */
    protected $balance = 0;

    /**
     * @var string
     * @ORM\Column(name="Usd_Skype", type="string", nullable=true)
     */
    protected $skype;

    /**
     * @Assert\Image(
     * minWidth="200",
     * minHeight="200",
     * maxSize="3M",
     * minHeightMessage="Минимальная высота 200px",
     * minWidthMessage="Минимальная ширина 200px",
     * maxSizeMessage="Файл не должен быть больше 3MB"
     * )
     * @ORM\Column(name="Usr_Avatar", type="array", nullable=true)
     * @FileStore\UploadableField(mapping="photo")
     */
    protected $avatar;

    /**
     * @Assert\Image(
     * minWidth="1000",
     * minHeight="300",
     * maxSize="3M",
     * minHeightMessage="Минимальная высота 300px",
     * minWidthMessage="Минимальная ширина 1000px",
     * maxSizeMessage="Файл не должен быть больше 3MB"
     * )
     * @ORM\Column(name="Usr_Cover", type="array", nullable=true)
     * @FileStore\UploadableField(mapping="photo")
     */
    protected $cover;


    /**
     * @return array
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param array $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    public function getFileName() {
        return 'usrava' . $this->getId() . "_" . time();
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
     * Set purse
     *
     * @param string $purse
     *
     * @return UserData
     */
    public function setPurse($purse)
    {
        $this->purse = $purse;

        return $this;
    }

    /**
     * Get purse
     *
     * @return string
     */
    public function getPurse()
    {
        return $this->purse;
    }


    /**
     * Set skype
     *
     * @param string $skype
     *
     * @return UserData
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * Get skype
     *
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * Set user
     *
     * @param \Core\UserBundle\Entity\User $user
     *
     * @return UserData
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
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param int $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return mixed
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param mixed $cover
     */
    public function setCover($cover)
    {
        $this->cover = $cover;
    }

    /**
     * @return int
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param int $about
     */
    public function setAbout($about)
    {
        $this->about = $about;
    }
}
