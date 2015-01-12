<?php

namespace SimpleBus\Message\Handler\Resolver;

use SimpleBus\Message\Message;
use SimpleBus\Message\Handler\Collection\MessageHandlerCollection;
use SimpleBus\Message\Handler\Resolver\Name\MessageNameResolver;

class NameBasedMessageHandlerResolver implements MessageHandlerResolver
{
    /**
     * @var MessageNameResolver
     */
    private $messageNameResolver;

    /**
     * @var \SimpleBus\Message\Handler\Collection\MessageHandlerCollection
     */
    private $messageHandlers;

    public function __construct(MessageNameResolver $messageNameResolver, MessageHandlerCollection $messageHandlers)
    {
        $this->messageNameResolver = $messageNameResolver;
        $this->messageHandlers = $messageHandlers;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Message $message)
    {
        $name = $this->messageNameResolver->resolve($message);

        return $this->messageHandlers->getByMessageName($name);
    }
}
