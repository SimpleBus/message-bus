<?php

namespace SimpleBus\Message\Recorder;

interface ContainsRecordedMessages
{
    /**
     * Fetch recorded messages.
     *
     * @return object[]
     */
    public function recordedMessages(): array;

    /**
     * Erase messages that were recorded since the last call to eraseMessages().
     */
    public function eraseMessages(): void;
}
