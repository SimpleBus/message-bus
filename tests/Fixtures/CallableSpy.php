<?php

namespace SimpleBus\Message\Tests\Fixtures;

class CallableSpy
{
    private int $hasBeenCalled = 0;

    /**
     * @var object[]
     */
    private array $receivedMessages = [];

    public function __invoke(object $message): void
    {
        ++$this->hasBeenCalled;
        $this->receivedMessages[] = $message;
    }

    public function hasBeenCalled(): int
    {
        return $this->hasBeenCalled;
    }

    /**
     * @return object[]
     */
    public function receivedMessages(): array
    {
        return $this->receivedMessages;
    }
}
