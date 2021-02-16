<?php

namespace SimpleBus\Message\Tests\Recorder\Fixtures;

use SimpleBus\Message\Recorder\ContainsRecordedMessages;

class ContainsRecordedMessagesStub implements ContainsRecordedMessages
{
    /**
     * @var object[]
     */
    private array $messages;

    /**
     * @param object[] $messages
     */
    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    public function eraseMessages(): void
    {
        $this->messages = [];
    }

    /**
     * @return object[]
     */
    public function recordedMessages(): array
    {
        return $this->messages;
    }
}
