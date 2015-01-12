<?php

namespace SimpleBus\Message\Handler;

use SimpleBus\Message\Bus;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use SimpleBus\Message\Message;
use SimpleBus\Message\Handler\Resolver\MessageHandlerResolver;

class DelegatesToMessageHandlerMiddleware implements MessageBusMiddleware
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
