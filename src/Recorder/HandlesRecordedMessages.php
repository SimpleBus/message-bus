<?php

namespace SimpleBus\Message\Recorder;

use SimpleBus\Message\Bus\MessageBus;

class HandlesRecordedMessages
{
    /**
     * @var ContainsRecordedMessages
     */
    private $messageRecorder;

    /**
     * @var MessageBus
     */
    private $messageBus;

    public function __construct(ContainsRecordedMessages $messageRecorder, MessageBus $messageBus)
    {
        $this->messageRecorder = $messageRecorder;
        $this->messageBus = $messageBus;
    }

    public function dispatch()
    {
        $recordedMessages = $this->messageRecorder->recordedMessages();

        $this->messageRecorder->eraseMessages();

        foreach ($recordedMessages as $recordedMessage) {
            $this->messageBus->handle($recordedMessage);
        }
    }
}