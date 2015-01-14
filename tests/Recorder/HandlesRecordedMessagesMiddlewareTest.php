<?php

namespace SimpleBus\Message\Tests\Recorder;

use SimpleBus\Message\Message;
use SimpleBus\Message\Recorder\HandlesRecordedMessagesMiddleware;
use SimpleBus\Message\Tests\Fixtures\NextCallableSpy;
use SimpleBus\Message\Tests\Recorder\Fixtures\MessageRecorderStub;

class HandlesRecordedMessagesMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_handles_recorded_messages()
    {
        $messages = [$this->dummyMessage(), $this->dummyMessage()];
        $messageRecorder = $this->mockMessageRecorder();

        // first recorded messages should be fetched
        $messageRecorder
            ->expects($this->at(0))
            ->method('recordedMessages')
            ->will($this->returnValue($messages));

        // then immediately erased
        $messageRecorder
            ->expects($this->at(1))
            ->method('eraseMessages');

        $actuallyHandledMessages = [];
        $messageBus = $this->messageBusSpy($actuallyHandledMessages);
        $middleware = new HandlesRecordedMessagesMiddleware(
            $messageRecorder,
            $messageBus
        );

        $next = new NextCallableSpy();

        $middleware->handle($this->dummyMessage(), $next);
        $this->assertSame(1, $next->hasBeenCalled());
        $this->assertSame($messages, $actuallyHandledMessages);
    }

    private function messageBusSpy(array &$actuallyHandledMessages)
    {
        $messageBus = $this->getMock('SimpleBus\Message\Bus\MessageBus');

        $messageBus
            ->expects($this->any())
            ->method('handle')
            ->will(
                $this->returnCallback(
                    function (Message $message) use (&$actuallyHandledMessages) {
                        $actuallyHandledMessages[] = $message;
                    }
                )
            );

        return $messageBus;
    }

    private function dummyMessage()
    {
        return $this->getMock('SimpleBus\Message\Message');
    }

    private function mockMessageRecorder()
    {
        return $this->getMock('SimpleBus\Message\Recorder\RecordsMessages');
    }
}
