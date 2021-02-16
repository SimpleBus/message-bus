<?php

namespace SimpleBus\Message\Recorder;

/**
 * Use this trait in classes which implement ContainsRecordedMessages to privately record and later release Message
 * instances, like events.
 */
trait PrivateMessageRecorderCapabilities
{
    /**
     * @var object[]
     */
    private array $messages = [];

    /**
     * @return object[]
     */
    public function recordedMessages(): array
    {
        return $this->messages;
    }

    public function eraseMessages(): void
    {
        $this->messages = [];
    }

    /**
     * Record a message.
     */
    protected function record(object $message): void
    {
        $this->messages[] = $message;
    }
}
