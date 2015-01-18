<?php

namespace SimpleBus\Message\Tests\Recorder\Fixtures;

use SimpleBus\Message\Message;
use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;

class PrivateMessageRecorderCapabilitiesStub implements ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    public function publicRecord(Message $message)
    {
        $this->record($message);
    }
}
