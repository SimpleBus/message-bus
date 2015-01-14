<?php

namespace SimpleBus\Message\Recorder;

use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use SimpleBus\Message\Message;

class HandlesRecordedMessagesMiddleware implements MessageBusMiddleware
{
    /**
     * @var RecordsMessages
     */
    private $messageRecorder;

    /**
     * @var MessageBus
     */
    private $messageBus;

    public function __construct(RecordsMessages $messageRecorder, MessageBus $messageBus)
    {
        $this->messageRecorder = $messageRecorder;
        $this->messageBus = $messageBus;
    }

    public function handle(Message $message, callable $next)
    {
        $next($message);

        $recordedMessages = $this->messageRecorder->recordedMessages();

        $this->messageRecorder->eraseMessages();

        foreach ($recordedMessages as $recordedMessage) {
            $this->messageBus->handle($recordedMessage);
        }
    }
}
