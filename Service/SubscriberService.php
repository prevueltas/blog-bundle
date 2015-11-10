<?php

namespace Prh\BlogBundle\Service;

use Prh\BlogBundle\Entity\SubscriberRepository;
use Prh\BlogBundle\Entity\Subscriber;

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
     * Constructor.
     *
     * @param SubscriberRepository $subscriberRepository
     */
    public function __construct(SubscriberRepository $subscriberRepository)
    {
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
}
