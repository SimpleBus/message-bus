<?php

namespace SimpleBus\Message\Subscriber\Resolver;

use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\Name\MessageNameResolver;

class NameBasedMessageSubscriberResolver implements MessageSubscribersResolver
{
    private MessageNameResolver $messageNameResolver;

    private CallableCollection $messageSubscribers;

    public function __construct(MessageNameResolver $messageNameResolver, CallableCollection $messageSubscribers)
    {
        $this->messageNameResolver = $messageNameResolver;
        $this->messageSubscribers = $messageSubscribers;
    }

    public function resolve(object $message): array
    {
        $name = $this->messageNameResolver->resolve($message);

        return $this->messageSubscribers->filter($name);
    }
}
