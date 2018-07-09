<?php
namespace Core\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BalanceController extends Controller {

    /**
     * Пополнение баланса
     * @Security("has_role( 'ROLE_USER')")
     * @Template
     * @param Request $request
     * @return array
     */
    public function replenishAction(Request $request) {
        $data = [];
        $data['page']['title'] = 'Пополнение баланса';
        $user = $this->getUser();
        $amount = $request->get('amount');
        $em = $this->getDoctrine()->getManager();
        $notification = $this->get('core.notification');
        $transaction = $this->get('core.transaction');

        if (! $amount) {
            return $data;
        } else if ( ! is_numeric($amount) and $amount > 0) {
            throw $this->createNotFoundException("Страница не найдена");
        }
        
        $balance = ($amount + $user->getBalance());

        $data['amount'] = $amount;
        $data['balance'] = $balance;
        $data['success'] = true;

        //оповещаем администрацию
        $notification = $notification->create(
            'balance_replenish',
            'Пополнение баланса',
            'Ваш баланс пополнен на $' . $amount,
            $user->getId(),
            $user
        );

        $transaction->add(
            $user,
            $amount,
            $notification
        );

        $user->setBalance($balance);
        $em->persist($user);
        $em->flush();
        $response = new JsonResponse($data);

        return $response;
    }
}