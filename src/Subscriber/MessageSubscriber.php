<?php

namespace SimpleBus\Message\Subscriber;

use SimpleBus\Message\Message;

interface MessageSubscriber
{
    /**
     * Provide the given message as a notification to this subscriber
     *
     * @param Message $message
     * @return void
     */
    public function notify(Message $message);
}
