<?php

namespace SimpleBus\Message\Tests\Recorder;

use SimpleBus\Message\Tests\Recorder\Fixtures\PrivateMessageRecorderCapabilitiesStub;

class MessageRecorderCapabilitiesStubTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_records_messages()
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
    public function it_erases_messages()
    {
        $messageRecorder = new PrivateMessageRecorderCapabilitiesStub();
        $messageRecorder->publicRecord($this->dummyMessage());
        $messageRecorder->publicRecord($this->dummyMessage());

        $messageRecorder->eraseMessages();

        $this->assertSame([], $messageRecorder->recordedMessages());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }
}
