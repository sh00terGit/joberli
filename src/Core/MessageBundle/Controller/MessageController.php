<?php

namespace Core\MessageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\MessageBundle\Provider\ProviderInterface;

class MessageController extends ContainerAware
{
    /**
     * Displays the authenticated participant inbox
     * @Security("has_role( 'ROLE_USER')")
     * @return Response
     */
    public function inboxAction()
    {
        $data = [];
        $data['page']['title'] = 'Сообщения';
        $data['page']['userMenu'] = true;
        $data['threads'] = $this->getProvider()->getInboxThreads();

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:inbox.html.twig', $data);
    }

    /**
     * @Template()
     * @param Request $request
     * @param $recipient
     * @return array|NotFoundHttpException
     * @Security("has_role( 'ROLE_USER')")
     */
    public function createDialogAction(Request $request, $recipient) {
        $data = [];
        $em = $this->container->get('doctrine.orm.entity_manager');

        $data['sender'] = $this->container->get('security.context')->getToken()->getUser();
        $data['recipient'] = $em->getRepository('UserBundle:User')->find($recipient);
        $threadBuilder = $this->container->get('fos_message.composer')->newThread();
        $sender = $this->container->get('fos_message.sender');

        if (! $data['recipient'] or ! $data['sender']) {
            return new NotFoundHttpException();
        }


        if ($request->get('message')) {
            $threadBuilder
                ->addRecipient($data['recipient']) // Retrieved from your backend, your user manager or ...
                ->setSender($data['sender'])
                ->setSubject('Личное сообщение')
                ->setBody($request->get('message'));

            $sender->send($threadBuilder->getMessage());
        }

        return $data;
    }

    /**
     * Displays the authenticated participant messages sent
     * @Security("has_role( 'ROLE_USER')")
     * @return Response
     */
    public function sentAction()
    {
        $threads = $this->getProvider()->getSentThreads();

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:sent.html.twig', array(
            'threads' => $threads
        ));
    }

    /**
     * Displays the authenticated participant deleted threads
     * @Security("has_role( 'ROLE_USER')")
     * @return Response
     */
    public function deletedAction()
    {
        $threads = $this->getProvider()->getDeletedThreads();

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:deleted.html.twig', array(
            'threads' => $threads
        ));
    }

    /**
     * Displays a thread, also allows to reply to it
     * @Security("has_role( 'ROLE_USER')")
     * @param string $threadId the thread id
     *
     * @return Response
     */
    public function threadAction($threadId)
    {
        $data = [];
        $data['page']['title'] = 'Диалог';

        $templating = $this->container->get('templating');
        $thread = $this->getProvider()->getThread($threadId);
        $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($form)) {
            $data = $templating->render('@Message/Message/block/messages.html.twig',
                array(
                    'messages'=>[$thread->getLastMessage()]
                )
            );

            return new Response($data);

        }

        $data['form'] = $form->createView();
        $data['thread'] = $thread;

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:thread.html.twig', $data);
    }

    /**
     * Displays a thread, also allows to reply to it
     * @Security("has_role( 'ROLE_USER')")
     * @param string $threadId the thread id
     *
     * @return Response
     */
    public function getMessagesAction($threadId)
    {
        $data = [];

        $thread = $this->getProvider()->getThread($threadId);
        $data['thread'] = $thread;

        $data = new JsonResponse($data);

        return $data;
    }

    /**
     * Create a new message thread
     * @Security("has_role( 'ROLE_USER')")
     * @return Response
     */
    public function newThreadAction()
    {
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');
        
        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:newThread.html.twig', array(
            'form' => $form->createView(),
            'data' => $form->getData()
        ));
    }

    /**
     * Deletes a thread
     * 
     * @param string $threadId the thread id
     * @Security("has_role( 'ROLE_USER')")
     * @return RedirectResponse
     */
    public function deleteAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $this->container->get('fos_message.deleter')->markAsDeleted($thread);
        $this->container->get('fos_message.thread_manager')->saveThread($thread);

        return new RedirectResponse($this->container->get('router')->generate('fos_message_inbox'));
    }
    
    /**
     * Undeletes a thread
     * 
     * @param string $threadId
     * @Security("has_role( 'ROLE_USER')")
     * @return RedirectResponse
     */
    public function undeleteAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $this->container->get('fos_message.deleter')->markAsUndeleted($thread);
        $this->container->get('fos_message.thread_manager')->saveThread($thread);

        return new RedirectResponse($this->container->get('router')->generate('fos_message_inbox'));
    }

    /**
     * Searches for messages in the inbox and sentbox
     * @Security("has_role( 'ROLE_USER')")
     * @return Response
     */
    public function searchAction()
    {
        $query = $this->container->get('fos_message.search_query_factory')->createFromRequest();
        $threads = $this->container->get('fos_message.search_finder')->find($query);

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:search.html.twig', array(
            'query' => $query,
            'threads' => $threads
        ));
    }

    /**
     * Gets the provider service
     * @Security("has_role( 'ROLE_USER')")
     * @return ProviderInterface
     */
    protected function getProvider()
    {
        return $this->container->get('fos_message.provider');
    }
}
