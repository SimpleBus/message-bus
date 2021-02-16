<?php

namespace SimpleBus\Message\Bus\Middleware;

interface MessageBusMiddleware
{
    /**
     * The provided $next callable should be called whenever the next middleware should start handling the message.
     * Its only argument should be a Message object (usually the same as the originally provided message).
     */
    public function handle(object $message, callable $next): void;
}
