<?php

namespace SimpleBus\Message\Recorder;

class PublicMessageRecorder implements RecordsMessages
{
    private $messages = [];

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
        $this->messages = [];
    }

    /**
     * Record a message.
     *
     * @param object $message
     */
    public function record($message)
    {
        $this->messages[] = $message;
    }
}
