<?php

namespace SimpleBus\Message\Tests\Recorder;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Recorder\PublicMessageRecorder;

class PublicMessageRecorderTest extends TestCase
{
    /**
     * @test
     */
    public function itRecordsMessages()
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
    public function itErasesMessages()
    {
        $messageRecorder = new PublicMessageRecorder();
        $messageRecorder->record($this->dummyMessage());
        $messageRecorder->record($this->dummyMessage());

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
