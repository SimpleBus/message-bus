<?php

namespace SimpleBus\Message\Tests\Recorder\Fixtures;

use SimpleBus\Message\Recorder\PrivateMessageRecorderCapabilities;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;

class PrivateMessageRecorderCapabilitiesStub implements ContainsRecordedMessages
{
    use PrivateMessageRecorderCapabilities;

    public function publicRecord($message)
    {
        $this->record($message);
    }
}
