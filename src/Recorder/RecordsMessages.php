<?php

namespace SimpleBus\Message\Recorder;

interface RecordsMessages extends ContainsRecordedMessages
{
    /**
     * Record a message.
     */
    public function record(object $message): void;
}
