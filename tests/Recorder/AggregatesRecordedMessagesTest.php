<?php

namespace SimpleBus\Message\Tests\Recorder;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Recorder\AggregatesRecordedMessages;
use SimpleBus\Message\Tests\Recorder\Fixtures\ContainsRecordedMessagesStub;

class AggregatesRecordedMessagesTest extends TestCase
{
    /**
     * @test
     */
    public function itCollectsMessagesRecordedByAllMessageRecorders()
    {
        $message1 = $this->dummyMessage();
        $message2 = $this->dummyMessage();

        $aggregator = new AggregatesRecordedMessages(
            [
                new ContainsRecordedMessagesStub([$message1]),
                new ContainsRecordedMessagesStub([$message2]),
            ]
        );

        $this->assertSame(
            [
                $message1,
                $message2,
            ],
            $aggregator->recordedMessages()
        );
    }

    /**
     * @test
     */
    public function itErasesMessagesRecordedByAllMessageRecorders()
    {
        $message1 = $this->dummyMessage();
        $messageRecorder1 = new ContainsRecordedMessagesStub([$message1]);

        $message2 = $this->dummyMessage();
        $messageRecorder2 = new ContainsRecordedMessagesStub([$message2]);

        $aggregator = new AggregatesRecordedMessages(
            [
                $messageRecorder1,
                $messageRecorder2,
            ]
        );

        $aggregator->eraseMessages();

        $this->assertSame([], $messageRecorder1->recordedMessages());
        $this->assertSame([], $messageRecorder2->recordedMessages());
    }

    private function dummyMessage()
    {
        return new \stdClass();
    }
}
