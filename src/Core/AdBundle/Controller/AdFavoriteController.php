<?php
namespace Core\AdBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class AdFavoriteController extends Controller
{


    /**
     * @param Request $request
     * @return JsonResponse
     * @internal param $id
     * @Security("has_role( 'ROLE_USER')")
     */
    public function addOrRemoveAction(Request $request)
    {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if (!$id = $request->get('id')) {
            new MissingOptionsException('Не передан параметр', array('id'));
        }

        if (!$user) {
            throw $this->createNotFoundException('Пользователь не найден');
        }


        $ad = $this->getDoctrine()->getRepository('AdBundle:Ad')
            ->find($id);

        if (!$ad) {
            throw $this->createNotFoundException('Объявление не найдено');
        }

        if ($ad->getIsUserFavorite($user)) {
            $ad->removeUserFavorite($user);
            $data['action'] = 'remove';
        } else {
            $ad->addUserFavorite($user);
            $data['action'] = 'add';
        }

        $em->persist($ad);
        $em->flush();

        $response = new JsonResponse($data);

        return $response;
    }
}