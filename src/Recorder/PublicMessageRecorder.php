<?php

namespace SimpleBus\Message\Recorder;

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
     * @param object $message
     */
    public function record($message)
    {
        $this->messages[] = $message;
    }
}
