<?php
namespace Core\AdBundle\Entity;
use Doctrine\ORM\EntityRepository;

class AdRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('dateCreated' => 'DESC'));
    }
}
