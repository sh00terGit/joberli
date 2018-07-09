<?php

namespace Core\AdBundle\Controller;

use Core\AdBundle\Entity\AdOrder;
use Core\AdBundle\Entity\AdRating;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdOrderController extends Controller
{

    /**
     * Покупки
     * @Template()
     * @return array
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction(Request $request) {
        $data = [];
        $user = $this->getUser();
        $data['page']['userMenu'] = true;
        $data['page']['title'] = 'Покупки';
        $payStatus = $request->query->get('payStatus');

        if ( $payStatus == null) {
            $payStatus = 1;
        }

        $data['orders'] = $this->getDoctrine()->getRepository('AdBundle:AdOrder')
            ->findBy(
                array(
                    'seller' => $user,
                    'payStatus' => $payStatus,
                )
            );


        return $data;
    }



    /**
     * @param $id
     * @Template
     * @return array
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction($id) {
        $data = [];
        $user = $this->getUser();
        $provider = $this->get('fos_message.provider');

        $data['page']['userMenu'] = true;

        $data['order'] = $this->getDoctrine()->getRepository('AdBundle:AdOrder')->createQueryBuilder('o')
            ->where('o.id = ' . $id)
            ->andWhere('o.seller = ' . $user->getId() . ' or o.venditore = ' . $user->getId())
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if ( ! $data['order'] ) {
            throw new NotFoundHttpException();
        }

        if ($data['order']->getDialog()) {
            $data['dialog'] = $provider->getThread($data['order']->getDialog());
            $data['form'] = $this->container->get('fos_message.reply_form.factory')
                ->create($data['dialog'])
                ->createView();
        }

        $data['page']['title'] = $data['order']->getTitle();

        return $data;
    }

    /**
     * Покупки
     * @Template()
     * @return array
     * @Security("has_role('ROLE_USER')")
     */
    public function removeAction(Request $request, $id) {
        $data = [];
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $data['order'] = $this->getDoctrine()->getRepository('AdBundle:AdOrder')->findOneBy(
            array(
                'id' => $id,
                'seller' => $user,
                'payStatus' => 0
            )
        );

        if ( ! $data['order'] ) {
            return new NotFoundHttpException();
        }


        if ($request->query->get('action') == 'remove') {

            $em->remove($data['order']);
            $em->flush();

            $data['alert'] = [
                'type' => 'success',
                'text' => 'Успешно удалено'
            ];

            $data['success'] = $this->renderView('CoreBundle:Bootstrap:Alert.html.twig', $data);
            $response = new JsonResponse($data);
            return $response;
        } else {
            return $this->render('@Ad/AdOrder/remove.html.twig', $data);
        }

    }


    /**
     * Покупки
     * @Template()
     * @return array
     * @Security("has_role('ROLE_USER')")
     */
    public function adoptAction(Request $request, $id) {
        $data = [];
        $em = $this->getDoctrine()->getManager();
        $taskManager = $this->get('ad.manager.task');
        $user = $this->getUser();
        $data['order'] = $this->getDoctrine()->getRepository('AdBundle:AdOrder')->findOneBy(
            array(
                'id' => $id,
                'venditore' => $user,
                'status' => 0,
                'payStatus' => true
            )
        );

        if ( ! $data['order'] ) {
            return new NotFoundHttpException();
        }


        if ($request->query->get('action') == 'adopt') {
            $data['order']->setAdoptDate(new DateTime('NOW'));
            $data['order']->setStatus(1);
            $em->flush();

            $taskManager->addTask($user, $data['order']);

            $data['alert'] = [
                'type' => 'success',
                'text' => 'Заказ переведен в стату выполнение'
            ];

            $data['status'] = $this->renderView('@Ad/AdOrder/block/status.html.twig', $data);

            $data['success'] = $this->renderView('CoreBundle:Bootstrap:Alert.html.twig', $data);
            $response = new JsonResponse($data);
            return $response;
        } else {
            return $this->render('@Ad/AdOrder/adopt.html.twig', $data);
        }

    }


    /**
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $order = new AdOrder();
        $data = [];
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $adId = $request->get('ad');
        $subAdsId = $request->get('subAd');

        if (
            ! $adId or
            ! $ad = $this->getDoctrine()->getRepository('AdBundle:Ad')->find($adId)
        ) {
            throw $this->createNotFoundException('Обьявление не найдено');
        }

        $order->setAd($ad);
        $order->setSeller($user);
        $order->setPrice($ad->getPrice());
        $order->setAmount($ad->getPrice());
        $order->setTitle($ad->getTitle());
        $order->setVenditore($ad->getUserCreate());
        $order->setExecutDays($ad->getExecutDays());
        $order->setExecutDaysAmount($ad->getExecutDays());


        if ($subAdsId) {
            foreach ($subAdsId as $item) {
                if (
                    $subAd = $this->getDoctrine()->getRepository('AdBundle:AdSub')->findOneBy(
                        array(
                            'id' => $item,
                            'ad' => $ad
                        )
                    )
                ) {
                    $order->addSubAd($subAd);
                    $order->setExecutDaysAmount($order->getExecutDaysAmount()+$subAd->getExecutDays());
                    $order->setAmount($order->getAmount()+$subAd->getPrice());
                }
            }
        }

        $em->persist($order);
        $em->flush();

        $data['redirect'] = $this->generateUrl('ad_order_pay', array('id' => $order->getId()));
        $response = new JsonResponse($data);

        return $response;
    }

    /**
     * Оплата заказа
     * @param $id
     * @Template
     * @return array
     */
    public function payAction(Request $request, $id) {
        $data = [];
        $data['page']['title'] = 'Оплата заказа';
        $user = $this->getUser();
        $pay = $request->get('action');
        $em = $this->getDoctrine()->getManager();

        if (
            ! $user OR
            ! $order = $this->getDoctrine()->getRepository('AdBundle:AdOrder')->findOneBy(
                array(
                    'id' => $id,
                    'seller' => $user,
                    'payStatus' => 0
                )
            )
        ) {
            throw $this->createNotFoundException('Заказ не найден');
        }

        $data['order'] = $order;

        if ( ! $pay) {
            return $data;
        }


        if ($user->getBalance() >= $order->getPrice()) {
            $order->setPayStatus(1);
            $user->setBalance($user->getBalance() - $order->getPrice());

            $composer = $this->get('fos_message.composer');
            $sender = $this->get('fos_message.sender');


            $data['success'] = true;

            $message = $composer->newThread()
                ->setSender($user)
                ->addRecipient($order->getVenditore())
                ->setSubject('Заказ #' . $order->getId())
                ->setBody('Здравствуйте')
                ->getMessage();

            $sender->send($message);

            $order->setDialog($message->getThread());

            $em->persist($order);
            $em->persist($user);
            $em->flush();
        } else {
            $data['error'] = true;
            $data['msg'] = 'Не хватает средств на балансе';
        }

        $response = new JsonResponse($data);

        return $response;
    }

    /**
     * Закрывает заказ
     * @Template()
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     * @param $id
     * @return array
     */
    public function finishAction(Request $request, $id) {
        $data = [];

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $adStat = $this->get('ad.manager.stat');
        $level = $this->get('ad.manager.level');
        $rating = $request->get('rating');
        $comment = $request->get('comment');
        $validator = $this->get('validator');
        $adRating = new AdRating();

        $notification = $this->get('core.notification');
        $transaction = $this->get('core.transaction');

        $order = $this->getDoctrine()->getRepository('AdBundle:AdOrder')
            ->findOneBy(
                array(
                    'id' => $id,
                    'status' => 1,
                    'seller' => $user,
                    'payStatus' => 1
                )
            );

        if ( ! $order) {
            $this->createNotFoundException('Заказ не найден');
        }

        if ($request->isMethod('POST')) {

            if (!$comment or !$rating) {
                $data = [
                    'status' => 'danger',
                    'message' => 'Все поля обязательны для заполнения',
                ];

                $data = new JsonResponse($data);
                return $data;
            }

            $adRating->setAd($order->getAd());
            $adRating->setComment($comment);
            $adRating->setUser($order->getVenditore());
            $adRating->setOrder($order);
            $adRating->setRate($rating);

            $order->setStatus(2);

            $errors = $validator->validate($adRating);
            if (count($errors) > 0) {
                $data = [
                    'status' => 'danger',
                    'message' => (string) $errors,
                ];

                $data = new JsonResponse($data);
                return $data;
            }

            $em->persist($adRating);
            $em->persist($order);


            //оповещаем администрацию
            $notification = $notification->create(
                'balance_replenish',
                'Задание выполнено',
                'Ваш баланс пополнен на $' . $order->getAmount(),
                $order->getVenditore()->getId(),
                $order->getVenditore()
            );

            $transaction->add(
                $order->getVenditore(),
                $order->getAmount(),
                $notification
            );

            $em->flush();


            //обновляем рейтинг продовца
            $adStat->updateRatingUser($order->getVenditore());
            //обновляем Уровень
            $level->update($order->getVenditore());

            $data = [
                'status' => 'success',
                'message' => 'Спасибо за оценку',
            ];

            $data = new JsonResponse($data);
            return $data;
        }

        $data['order'] = $order;

        return $data;
    }
}
