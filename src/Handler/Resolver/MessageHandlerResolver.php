<?php

namespace SimpleBus\Message\Handler\Resolver;

interface MessageHandlerResolver
{
    /**
     * Resolve the message handler callable for the given message.
     */
    public function resolve(object $message): callable;
}
