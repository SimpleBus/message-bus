<?php

namespace SimpleBus\Message\Subscriber;

interface MessageSubscriber
{
    /**
     * Provide the given message as a notification to this subscriber
     *
     * @param object $message
     * @return void
     */
    public function notify($message);
}
