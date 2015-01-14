<?php

namespace SimpleBus\Message\Recorder;

use SimpleBus\Message\Message;

interface RecordsMessages
{
    /**
     * Fetch messages recorded by the object that uses this trait.
     *
     * @return Message[]
     */
    public function recordedMessages();

    /**
     * Erase messages that were recorded since the last call to eraseMessages()
     *
     * @return void
     */
    public function eraseMessages();
}
