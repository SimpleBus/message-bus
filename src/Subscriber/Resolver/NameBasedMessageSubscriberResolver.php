<?php

namespace SimpleBus\Message\Subscriber\Resolver;

use SimpleBus\Message\Name\MessageNameResolver;
use SimpleBus\Message\Subscriber\Collection\MessageSubscriberCollection;

class NameBasedMessageSubscriberResolver implements MessageSubscribersResolver
{
    /**
     * @var MessageNameResolver
     */
    private $messageNameResolver;

    /**
     * @var MessageSubscriberCollection
     */
    private $messageSubscribers;

    public function __construct(
        MessageNameResolver $messageNameResolver,
        MessageSubscriberCollection $messageSubscriberCollection
    ) {
        $this->messageNameResolver = $messageNameResolver;
        $this->messageSubscribers = $messageSubscriberCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($message)
    {
        $name = $this->messageNameResolver->resolve($message);

        return $this->messageSubscribers->subscribersByMessageName($name);
    }
}
