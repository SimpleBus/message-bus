<?php


namespace SimpleBus\Message\Recorder;

use SimpleBus\Message\Message;

/**
 * Use this trait in classes which implement RecordsMessages to record and later release Message instances, like events.
 */
trait MessageRecorderCapabilities
{
    private $messages = array();

    /**
     * Fetch messages recorded by the object that uses this trait.
     *
     * @return Message[]
     */
    public function recordedMessages()
    {
        return $this->messages;
    }

    public function eraseMessages()
    {
        $this->messages = array();
    }

    /**
     * Record a message within the object that uses this trait.
     *
     * @param Message $message
     */
    protected function record(Message $message)
    {
        $this->messages[] = $message;
    }
}
