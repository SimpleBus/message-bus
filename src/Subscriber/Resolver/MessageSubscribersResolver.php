<?php

namespace SimpleBus\Message\Subscriber\Resolver;

use SimpleBus\Message\Subscriber\MessageSubscriber;

interface MessageSubscribersResolver
{
    /**
     * Resolve the MessageSubscriber instances that should be notified
     *
     * @param object $message
     * @return MessageSubscriber[]
     */
    public function resolve($message);
}
