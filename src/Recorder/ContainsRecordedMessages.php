<?php

namespace SimpleBus\Message\Recorder;

use SimpleBus\Message\Message;

interface ContainsRecordedMessages
{
    /**
     * Fetch recorded messages.
     *
     * @return Message[]
     */
    public function recordedMessages();

    /**
     * Erase messages that were recorded since the last call to eraseMessages().
     *
     * @return void
     */
    public function eraseMessages();
}
