<?php

namespace Prh\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Prh\BlogBundle\Entity\Subscriber;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SubscriberController.
 *
 * @Route("/admin/subscriber")
 */
class SubscriberController extends Controller
{
    /**
     * List all the subscribers.
     *
     * @return Response
     *
     * @Route("/list", name="prh_blog_subscriber_list")
     */
    public function listAction()
    {
        $subscriberService = $this->get('prh.blog.service.subscriber');

        return $this->render(
            'PrhBlogBundle:default:subscribers.html.twig',
            [
                'subscribers' => $subscriberService->getSubscribers()
            ]
        );
    }

    /**
     * Create a new subscriber.
     *
     * @return Response
     *
     * @Route("/new", name="prh_blog_subscriber_new")
     */
    public function newAction()
    {
        $subscriberService = $this->get('prh.blog.service.subscriber');

        return $this->render(
            'PrhBlogBundle:default:subscriber.html.twig',
            [
                'form' => $subscriberService->createForm(new Subscriber())->createView()
            ]
        );
    }

    /**
     * Update a subscriber.
     *
     * @param subscriber $subscriber
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/edit/{id}", name="prh_blog_subscriber_edit")
     * @ParamConverter("subscriber", class="PrhBlogBundle:subscriber")
     */
    public function editAction(subscriber $subscriber)
    {
        $subscriberService = $this->get('prh.blog.service.subscriber');

        $preview = false;
        if (null !== $this->get('router')->getRouteCollection()->get('app_subscriber')) {
            $preview = true;
        }

        return $this->render(
            'PrhBlogBundle:default:subscriber.html.twig',
            [
                'subscriber' => $subscriber,
                'form' => $subscriberService->createForm($subscriber)->createView(),
                'preview' => $preview
            ]
        );
    }

    /**
     * Create a subscriber.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/create", name="prh_blog_subscriber_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $subscriberService = $this->get('prh.blog.service.subscriber');

        if ($subscriber = $subscriberService->processForm($request)) {
            $this->get('session')->getFlashBag()->add('success', 'The subscriber has been created.');

            return $this->redirectToRoute('prh_blog_subscriber_edit', ['id' => $subscriber->getId()]);
        }

        $this->get('session')->getFlashBag()->add('error', 'Oops! Please try again.');

        return $this->redirectToRoute('prh_blog_subscriber_new');
    }

    /**
     * Update a subscriber.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param subscriber $subscriber
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/update/{id}", name="prh_blog_subscriber_update")
     * @Method("POST")
     * @ParamConverter("subscriber", class="PrhBlogBundle:subscriber")
     */
    public function updateAction(Request $request, subscriber $subscriber)
    {
        $subscriberService = $this->get('prh.blog.service.subscriber');

        if ($subscriberService->processForm($request, $subscriber)) {
            $this->get('session')->getFlashBag()->add('success', 'The subscriber has been updated.');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Oops! Please try again.');
        }

        return $this->redirectToRoute('prh_blog_subscriber_edit', ['id' => $subscriber->getId()]);
    }

    /**
     * Delete a subscriber.
     *
     * @param subscriber $subscriber
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/delete/{id}", name="prh_blog_subscriber_delete")
     * @ParamConverter("subscriber", class="PrhBlogBundle:subscriber")
     */
    public function deleteAction(subscriber $subscriber)
    {
        $subscriberService = $this->get('prh.blog.service.subscriber');
        $subscriberService->delete($subscriber);

        $this->addFlash('success', 'The subscriber has been removed');

        return $this->redirectToRoute('prh_blog_subscriber_list');
    }
}
