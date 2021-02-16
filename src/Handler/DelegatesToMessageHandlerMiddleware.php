<?php

namespace SimpleBus\Message\Handler;

use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use SimpleBus\Message\Handler\Resolver\MessageHandlerResolver;

class DelegatesToMessageHandlerMiddleware implements MessageBusMiddleware
{
    private MessageHandlerResolver $messageHandlerResolver;

    public function __construct(MessageHandlerResolver $messageHandlerResolver)
    {
        $this->messageHandlerResolver = $messageHandlerResolver;
    }

    /**
     * Handles the message by resolving the correct message handler and calling it.
     */
    public function handle(object $message, callable $next): void
    {
        $handler = $this->messageHandlerResolver->resolve($message);
        call_user_func($handler, $message);

        $next($message);
    }
}
