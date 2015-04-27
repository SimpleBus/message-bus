<?php

namespace SimpleBus\Message\Tests\CallableResolver\Fixtures;

use SimpleBus\Message\Tests\Handler\Resolver\Fixtures\DummyMessage;

class DecoupledHandler
{
    public function __construct($argument1)
    {
    }

    public function handleDummyMessage(DummyMessage $message)
    {
    }
}
