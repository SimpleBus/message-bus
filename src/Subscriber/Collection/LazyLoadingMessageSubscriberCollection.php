<?php

namespace SimpleBus\Message\Subscriber\Collection;

use Assert\Assertion;

class LazyLoadingMessageSubscriberCollection implements MessageSubscriberCollection
{
    /**
     * @var array
     */
    private $messageSubscriberServiceIds;

    /**
     * @var callable
     */
    private $serviceLocator;

    public function __construct(array $messageSubscriberServiceIds, callable $serviceLocator)
    {
        $this->messageSubscriberServiceIds = $messageSubscriberServiceIds;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribersByMessageName($messageName)
    {
        if (!isset($this->messageSubscriberServiceIds[$messageName])) {
            return [];
        }

        Assertion::allString(
            $this->messageSubscriberServiceIds[$messageName],
            'Expected an array of strings (service ids)'
        );

        return array_map(
            [$this, 'loadSubscriberService'],
            $this->messageSubscriberServiceIds[$messageName]
        );
    }

    private function loadSubscriberService($subscriberServiceId)
    {
        $subscriber = call_user_func($this->serviceLocator, $subscriberServiceId);

        Assertion::isInstanceOf($subscriber, 'SimpleBus\Message\Subscriber\MessageSubscriber');

        return $subscriber;
    }
}
