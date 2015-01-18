<?php


namespace SimpleBus\Message\Recorder;

use SimpleBus\Message\Message;

/**
 * Use this trait in classes which implement ContainsRecordedMessages to privately record and later release Message
 * instances, like events.
 */
trait PrivateMessageRecorderCapabilities
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
    protected function record(Message $message)
    {
        $this->messages[] = $message;
    }
}
