<?php

namespace SimpleBus\Message\Subscriber\Collection;

use SimpleBus\Message\Subscriber\MessageSubscriber;

interface MessageSubscriberCollection
{
    /**
     * @return MessageSubscriber[]
     */
    public function subscribersByMessageName($messageName);
}
