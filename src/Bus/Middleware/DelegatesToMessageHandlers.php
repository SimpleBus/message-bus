<?php

namespace SimpleBus\Message\Bus\Middleware;

use SimpleBus\Message\Bus;
use SimpleBus\Message\Message;
use SimpleBus\Message\Handler\Resolver\MessageHandlerResolver;

class DelegatesToMessageHandlers implements MessageBusMiddleware
{
    private $messageHandlerResolver;

    public function __construct(MessageHandlerResolver $messageHandlerResolver)
    {
        $this->messageHandlerResolver = $messageHandlerResolver;
    }

    /**
     * Handles the message by resolving the correct MessageHandler and calling it.
     *
     * {@inheritdoc}
     */
    public function handle(Message $message, callable $next)
    {
        $this->messageHandlerResolver->resolve($message)->handle($message);

        $next($message);
    }
}
