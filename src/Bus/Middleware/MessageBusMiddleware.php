<?php

namespace SimpleBus\Message\Bus\Middleware;

use SimpleBus\Message\Message;

interface MessageBusMiddleware
{
    /**
     * The provided $next callable should be called whenever the next middleware should start handling the message.
     * Its only argument should be a Message object (usually the same as the originally provided message).
     *
     * @param Message $message
     * @param callable $next
     * @return void
     */
    public function handle(Message $message, callable $next);
}
