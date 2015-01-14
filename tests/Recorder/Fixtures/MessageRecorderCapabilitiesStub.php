<?php

namespace SimpleBus\Message\Tests\Recorder\Fixtures;

use SimpleBus\Message\Message;
use SimpleBus\Message\Recorder\MessageRecorderCapabilities;
use SimpleBus\Message\Recorder\RecordsMessages;

class MessageRecorderCapabilitiesStub implements RecordsMessages
{
    use MessageRecorderCapabilities;

    public function publicRecord(Message $message)
    {
        $this->record($message);
    }
}
