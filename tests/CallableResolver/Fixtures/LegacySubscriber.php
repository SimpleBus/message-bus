<?php

namespace SimpleBus\Message\Tests\CallableResolver\Fixtures;

class LegacySubscriber
{
    public function notify(object $message): void
    {
    }
}
