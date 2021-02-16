<?php

namespace SimpleBus\Message\Handler\Resolver;

use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\Name\MessageNameResolver;

class NameBasedMessageHandlerResolver implements MessageHandlerResolver
{
    private MessageNameResolver $messageNameResolver;

    private CallableMap $messageHandlers;

    public function __construct(MessageNameResolver $messageNameResolver, CallableMap $messageHandlers)
    {
        $this->messageNameResolver = $messageNameResolver;
        $this->messageHandlers = $messageHandlers;
    }

    public function resolve(object $message): callable
    {
        $name = $this->messageNameResolver->resolve($message);

        return $this->messageHandlers->get($name);
    }
}
