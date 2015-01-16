<?php

namespace SimpleBus\Message\Recorder;

use SimpleBus\Message\Message;

class PublicMessageRecorder implements RecordsMessages
{
    private $messages = array();

    /**
     * {@inheritdoc}
     */
    public function recordedMessages()
    {
        return $this->messages;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseMessages()
    {
        $this->messages = array();
    }

    /**
     * Record a message.
     *
     * @param Message $message
     */
    public function record(Message $message)
    {
        $this->messages[] = $message;
    }
}
