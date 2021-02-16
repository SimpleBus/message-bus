<?php

namespace SimpleBus\Message\Recorder;

class AggregatesRecordedMessages implements ContainsRecordedMessages
{
    /**
     * @var ContainsRecordedMessages[]
     */
    private array $messageRecorders;

    /**
     * @param ContainsRecordedMessages[] $messageRecorders
     */
    public function __construct(array $messageRecorders)
    {
        foreach ($messageRecorders as $messageRecorder) {
            $this->addMessageRecorder($messageRecorder);
        }
    }

    /**
     * Get messages recorded by all known message recorders.
     *
     * @return object[]
     */
    public function recordedMessages(): array
    {
        $allRecordedMessages = [];

        foreach ($this->messageRecorders as $messageRecorder) {
            $allRecordedMessages = array_merge($allRecordedMessages, $messageRecorder->recordedMessages());
        }

        return $allRecordedMessages;
    }

    /**
     * Erase messages recorded by all known message recorders.
     */
    public function eraseMessages(): void
    {
        foreach ($this->messageRecorders as $messageRecorder) {
            $messageRecorder->eraseMessages();
        }
    }

    private function addMessageRecorder(ContainsRecordedMessages $messageRecorder): void
    {
        $this->messageRecorders[] = $messageRecorder;
    }
}
