<?php

namespace SimpleBus\Message\Tests\Recorder;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Tests\Recorder\Fixtures\PrivateMessageRecorderCapabilitiesStub;
use stdClass;

/**
 * @internal
 * @coversNothing
 */
class MessageRecorderCapabilitiesStubTest extends TestCase
{
    /**
     * @test
     */
    public function itRecordsMessages(): void
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
    public function itErasesMessages(): void
    {
        $messageRecorder = new PrivateMessageRecorderCapabilitiesStub();
        $messageRecorder->publicRecord($this->dummyMessage());
        $messageRecorder->publicRecord($this->dummyMessage());

        $messageRecorder->eraseMessages();

        $this->assertSame([], $messageRecorder->recordedMessages());
    }

    private function dummyMessage(): stdClass
    {
        return new stdClass();
    }
}
