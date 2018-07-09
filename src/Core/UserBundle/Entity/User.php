<?php
namespace Core\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="User")
 */
class User extends BaseUser implements ParticipantInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->userData = new ArrayCollection();
        $this->adFavorite = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Core\AdBundle\Entity\Ad", mappedBy="userFavorite")
     */
    protected $adFavorite;

    /**
     * @ORM\Column(name="Usr_Rating", type="float", nullable=true)
     */
    protected $rating = 0;

    /**
     * @ORM\Column(name="Usr_Level", type="integer", nullable=true)
     */
    protected $level = 0;

    /**
     * @ORM\OneToMany(targetEntity="Core\CoreBundle\Entity\Notification", mappedBy="user")
     */
    protected $notifications;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="Usr_Date_Create")
     */
    private $dateCreated;

    /**
     * @ORM\OneToMany(targetEntity="UserData", mappedBy="user")
     */
    protected $userData;


    public function isOnline() {
        $lastLogin = $this->getLastLogin();

        $timer = new \DateTime($lastLogin->format('Y-m-d H:i:s'));
        $timer->add(new \DateInterval('PT' . 5 . 'M'));

        if ( $timer >= new \DateTime()) {
            return true;
        }
        return false;
    }

    public function getAvatarImg() {
        if (method_exists($this->getUserData(), 'getAvatar') and is_array($this->getUserData()->getAvatar())) {
            return $this->getUserData()->getAvatar()['path'];
        } else {
            return 'bundles/metronic/img/no-avatar.jpg';
        }
    }

    public function getCoverImg() {
        if (method_exists($this->getUserData(), 'getCover') and is_array($this->getUserData()->getCover())) {
            return $this->getUserData()->getCover()['path'];
        } else {
            return 'bundles/core/img/user-cover-pattern.jpg';
        }
    }

    /**
     * @return mixed
     */
    public function getUserData()
    {
        return $this->userData->first();
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
     * Add adFavorite
     *
     * @param \Core\AdBundle\Entity\Ad $adFavorite
     *
     * @return User
     */
    public function addAdFavorite(\Core\AdBundle\Entity\Ad $adFavorite)
    {
        $this->adFavorite[] = $adFavorite;

        return $this;
    }

    /**
     * Remove adFavorite
     *
     * @param \Core\AdBundle\Entity\Ad $adFavorite
     */
    public function removeAdFavorite(\Core\AdBundle\Entity\Ad $adFavorite)
    {
        $this->adFavorite->removeElement($adFavorite);
    }

    /**
     * Get adFavorite
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdFavorite()
    {
        return $this->adFavorite;
    }

    /**
     * Set userData
     *
     * @param \Core\UserBundle\Entity\UserData $userData
     *
     * @return User
     */
    public function setUserData(\Core\UserBundle\Entity\UserData $userData = null)
    {
        $this->userData = $userData;

        return $this;
    }


    public function setBalance($balance) {
        $userData = $this->getUserData();
        $userData->setBalance($balance);
    }


    public function getBalance() {
        $userData = $this->getUserData();
        return $userData->getBalance();
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
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }
}
