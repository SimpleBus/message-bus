<?php

namespace SimpleBus\Message\Tests\Handler\Resolver\Fixtures;

class DecoupledMessageHandler
{
    public $wasCalled = false;

    public function theHandlerMethod(DummyMessage $message)
    {
        $this->wasCalled = true;
    }
}
