<?php

namespace Prh\BlogBundle\Service;

use Prh\BlogBundle\Entity\SubscriberRepository;
use Prh\BlogBundle\Entity\Subscriber;
use Prh\BlogBundle\Form\SubscriberType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SubscriberService.
 */
class SubscriberService
{
    /**
     * @var SubscriberRepository
     */
    private $subscriberRepository;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var Router
     */
    private $router;

    /**
     * Constructor.
     *
     * @param SubscriberRepository $subscriberRepository
     */
    public function __construct(FormFactory $formFactory, Router $router, SubscriberRepository $subscriberRepository)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->subscriberRepository = $subscriberRepository;
    }

    /**
     * Get a subscriber.
     *
     * @param $subscriberId
     *
     * @return null|Subscriber
     */
    public function getSubscriber($subscriberId)
    {
        return $this->subscriberRepository->find($subscriberId);
    }

    /**
     * Get all the subscribers.
     *
     * @return array|Subscriber[]
     */
    public function getSubscribers()
    {
        return $this->subscriberRepository->findAll();
    }

    /**
     * Save a subscriber.
     *
     * @param Subscriber $subscriber
     */
    public function save(Subscriber $subscriber)
    {
        $this->subscriberRepository->save($subscriber);
    }

    /**
     * Delete a subscriber.
     *
     * @param Subscriber $subscriber
     */
    public function delete(Subscriber $subscriber)
    {
        $this->subscriberRepository->delete($subscriber);
    }

    /**
     * Create a subscriber form.
     *
     * @param Subscriber $subscriber
     *
     * @return Form
     */
    public function createForm(Subscriber $subscriber)
    {
        $route = $this->router->generate('prh_blog_subscriber_create');

        if ($subscriber->getId()) {
            $route = $this->router->generate('prh_blog_subscriber_update', ['id' => $subscriber->getId()]);
        }

        return $this->formFactory->create(
            new SubscriberType(),
            $subscriber,
            [
                'action' => $route,
                'method' => 'POST'
            ]
        );
    }

    /**
     * Process a submitted form.
     *
     * @param Request $request
     * @param Subscriber $subscriber
     *
     * @return Subscriber|null
     */
    public function processForm(Request $request, Subscriber $subscriber = null)
    {
        if (null === $subscriber) {
            $subscriber = new Subscriber();
        }

        $form = $this->createForm($subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subscriber = $form->getData();
            $this->save($subscriber);

            return $subscriber;
        }
    }
}
