<?php

namespace SimpleBus\Message\Subscriber\Resolver;

interface MessageSubscribersResolver
{
    /**
     * Resolve the message subscriber callables that should be notified.
     *
     * @return callable[]
     */
    public function resolve(object $message): array;
}
