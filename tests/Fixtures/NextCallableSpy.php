<?php

namespace SimpleBus\Message\Tests\Fixtures;

use SimpleBus\Message\Message;

class NextCallableSpy
{
    private $hasBeenCalled = 0;
    private $receivedMessages = [];

    public function __invoke(Message $message)
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
