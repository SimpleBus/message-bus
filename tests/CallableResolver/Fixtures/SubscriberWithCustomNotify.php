<?php

namespace SimpleBus\Message\Tests\CallableResolver\Fixtures;

class SubscriberWithCustomNotify
{
    public function customNotifyMethod(object $message): void
    {
    }
}
