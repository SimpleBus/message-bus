<?php

namespace SimpleBus\Message\Tests\Fixtures;

class CallableSpy
{
    private $hasBeenCalled = 0;
    private $receivedMessages = [];

    public function __invoke($message)
    {
        $this->hasBeenCalled++;
        $this->receivedMessages[] = $message;
    }

    public function hasBeenCalled()
    {
        return $this->hasBeenCalled;
    }

    public function receivedMessages()
    {
        return $this->receivedMessages;
    }
}
