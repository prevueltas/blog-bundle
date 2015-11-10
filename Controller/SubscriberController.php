<?php

namespace Prh\BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
}
