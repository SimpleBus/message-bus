<?php

namespace SimpleBus\Message\Tests\Recorder;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Tests\Recorder\Fixtures\PrivateMessageRecorderCapabilitiesStub;

class MessageRecorderCapabilitiesStubTest extends TestCase
{
    /**
     * @test
     */
    public function itRecordsMessages()
    {
        $messageRecorder = new PrivateMessageRecorderCapabilitiesStub();
        $message1 = $this->dummyMessage();
        $message2 = $this->dummyMessage();

        $messageRecorder->publicRecord($message1);
        $messageRecorder->publicRecord($message2);

        $this->assertSame([$message1, $message2], $messageRecorder->recordedMessages());
    }

    /**
     * @test
     */
    public function itErasesMessages()
    {
        $messageRecorder = new PrivateMessageRecorderCapabilitiesStub();
        $messageRecorder->publicRecord($this->dummyMessage());
        $messageRecorder->publicRecord($this->dummyMessage());

        $messageRecorder->eraseMessages();

        $this->assertSame([], $messageRecorder->recordedMessages());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }
}
