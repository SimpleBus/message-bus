<?php

namespace SimpleBus\Message\Handler\Resolver;

use SimpleBus\Message\Handler\MessageHandler;

interface MessageHandlerResolver
{
    /**
     * Resolve the MessageHandler for the given Message.
     *
     * @param object $message
     * @return MessageHandler
     */
    public function resolve($message);
}
