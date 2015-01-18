<?php

namespace SimpleBus\Message\Tests\Recorder\Fixtures;

use SimpleBus\Message\Recorder\ContainsRecordedMessages;

class ContainsRecordedMessagesStub implements ContainsRecordedMessages
{
    private $messages;

    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    public function eraseMessages()
    {
        $this->messages = [];
    }

    public function recordedMessages()
    {
        return $this->messages;
    }
}
