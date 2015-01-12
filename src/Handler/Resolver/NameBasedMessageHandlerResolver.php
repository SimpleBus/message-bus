<?php

namespace SimpleBus\Message\Handler\Resolver;

use SimpleBus\Message\Message;
use SimpleBus\Message\Handler\Map\MessageHandlerMap;
use SimpleBus\Message\Name\MessageNameResolver;

class NameBasedMessageHandlerResolver implements MessageHandlerResolver
{
    /**
     * @var MessageNameResolver
     */
    private $messageNameResolver;

    /**
     * @var \SimpleBus\Message\Handler\Map\MessageHandlerMap
     */
    private $messageHandlers;

    public function __construct(MessageNameResolver $messageNameResolver, MessageHandlerMap $messageHandlers)
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

        return $this->messageHandlers->handlerByMessageName($name);
    }
}
