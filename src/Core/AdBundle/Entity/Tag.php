<?php
namespace Core\AdBundle\Entity;

use FPN\TagBundle\Entity\Tag as BaseTag;
use Doctrine\ORM\Mapping as ORM;

/**
*
* @ORM\Table()
* @ORM\Entity
*/
class Tag extends BaseTag
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Tagging", mappedBy="tag", fetch="EAGER")
     **/
    protected $tagging;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}