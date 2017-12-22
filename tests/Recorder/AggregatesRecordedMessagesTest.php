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
    public function it_collects_messages_recorded_by_all_message_recorders()
    {
        $message1 = $this->dummyMessage();
        $message2 = $this->dummyMessage();

        $aggregator = new AggregatesRecordedMessages(
            array(
                new ContainsRecordedMessagesStub(array($message1)),
                new ContainsRecordedMessagesStub(array($message2))
            )
        );

        $this->assertSame(
            array(
                $message1,
                $message2
            ),
            $aggregator->recordedMessages()
        );
    }

    /**
     * @test
     */
    public function it_erases_messages_recorded_by_all_message_recorders()
    {
        $message1 = $this->dummyMessage();
        $messageRecorder1 = new ContainsRecordedMessagesStub(array($message1));

        $message2 = $this->dummyMessage();
        $messageRecorder2 = new ContainsRecordedMessagesStub(array($message2));

        $aggregator = new AggregatesRecordedMessages(
            array(
                $messageRecorder1,
                $messageRecorder2
            )
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
