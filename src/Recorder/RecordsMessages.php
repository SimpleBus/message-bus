<?php

namespace SimpleBus\Message\Recorder;

use SimpleBus\Message\Message;

interface RecordsMessages extends ContainsRecordedMessages
{
    /**
     * Record a message.
     *
     * @param Message $message
     */
    public function record(Message $message);
}
