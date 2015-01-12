<?php

namespace SimpleBus\Message\Subscriber\Resolver;

use SimpleBus\Message\Message;
use SimpleBus\Message\Subscriber\MessageSubscriber;

interface MessageSubscribersResolver
{
    /**
     * Resolve the MessageSubscriber instances that should be notified
     *
     * @param Message $message
     * @return MessageSubscriber[]
     */
    public function resolve(Message $message);
}
