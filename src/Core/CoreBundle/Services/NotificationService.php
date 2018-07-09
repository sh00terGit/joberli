<?php
namespace Core\CoreBundle\Services;

use Core\CoreBundle\Entity\Notification;
use Doctrine\Common\Persistence\ObjectManager;

class NotificationService {

    public $em;

    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }


    /**
     * @param $type
     * @param $title
     * @param $main
     * @param $attr
     * @param $from
     * @return Notification
     */
    public function create($type, $title, $main, $attr, $from) {
        $notification = new Notification();

        $notification->setType($type);
        $notification->setTitle($title);
        $notification->setMain($main);
        $notification->setAttr($attr);
        $notification->setNew(true);

        if ($from != 'admin') {
            $notification->setUser($from);
        } else {
            $notification->setGroup(
                array(
                    'ROLE_MODERATOR',
                    'ROLE_ADMIN'
                )
            );
        }

        $this->em->persist($notification);
        $this->em->flush();

        return $notification;
    }


    public function notNewTypeId($type, $id) {
        $notification = $this->em->getRepository('CoreBundle:Notification')
            ->findOneBy(
                array(
                    'type' => $type,
                    'attr' => $id,
                    'new' => true
                )
            );

        if (!$notification) {
            return true;
        }

        $notification->setNew(false);
        $this->em->persist($notification);
        $this->em->flush();

        return $notification;
    }


    public function notNew($id) {
        $notification = $this->em->getRepository('CoreBundle:Notification')
            ->findOneBy(
                array(
                    'id' => $id
                )
            );

        if (!$notification) {
            return true;
        }

        $notification->setNew(false);
        $this->em->persist($notification);
        $this->em->flush();

        return $notification;
    }
}