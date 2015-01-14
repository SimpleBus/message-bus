<?php

namespace SimpleBus\Message\Tests\Recorder;

use SimpleBus\Message\Tests\Recorder\Fixtures\MessageRecorderCapabilitiesStub;

class MessageRecorderCapabilitiesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_records_messages()
    {
        $messageRecorder = new MessageRecorderCapabilitiesStub();
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
        $messageRecorder = new MessageRecorderCapabilitiesStub();
        $messageRecorder->publicRecord($this->dummyMessage());
        $messageRecorder->publicRecord($this->dummyMessage());

        $messageRecorder->eraseMessages();

        $this->assertSame([], $messageRecorder->recordedMessages());
    }

    private function dummyMessage()
    {
        return $this->getMock('SimpleBus\Message\Message');
    }
}
