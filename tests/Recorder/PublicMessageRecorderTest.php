<?php

namespace SimpleBus\Message\Tests\Recorder;

use SimpleBus\Message\Recorder\PublicMessageRecorder;

class PublicMessageRecorderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_records_messages()
    {
        $messageRecorder = new PublicMessageRecorder();
        $message1 = $this->dummyMessage();
        $message2 = $this->dummyMessage();

        $messageRecorder->record($message1);
        $messageRecorder->record($message2);

        $this->assertSame([$message1, $message2], $messageRecorder->recordedMessages());
    }

    /**
     * @test
     */
    public function it_erases_messages()
    {
        $messageRecorder = new PublicMessageRecorder();
        $messageRecorder->record($this->dummyMessage());
        $messageRecorder->record($this->dummyMessage());

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
