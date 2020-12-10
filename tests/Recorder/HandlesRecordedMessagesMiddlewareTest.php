<?php

namespace SimpleBus\Message\Tests\Recorder;

use Exception;
use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Message\Recorder\HandlesRecordedMessagesMiddleware;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;
use SimpleBus\Message\Tests\Fixtures\CallableSpy;

class HandlesRecordedMessagesMiddlewareTest extends TestCase
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
            ->expects($this->once())
            ->method('recordedMessages')
            ->will($this->returnValue($messages));

        // then immediately erased
        $messageRecorder
            ->expects($this->once())
            ->method('eraseMessages');

        $actuallyHandledMessages = [];
        $messageBus = $this->messageBusSpy($actuallyHandledMessages);
        $middleware = new HandlesRecordedMessagesMiddleware(
            $messageRecorder,
            $messageBus
        );

        $next = new CallableSpy();

        $middleware->handle($this->dummyMessage(), $next);
        $this->assertSame(1, $next->hasBeenCalled());
        $this->assertSame($messages, $actuallyHandledMessages);
    }

    /**
     * @test
     */
    public function it_rethrows_a_caught_exception_but_first_clears_any_recorded_messages()
    {
        $messageRecorder = $this->mockMessageRecorder();

        $middleware = new HandlesRecordedMessagesMiddleware($messageRecorder, $this->dummyMessageBus());

        $exception = new Exception();
        $nextAlwaysFails = function() use ($exception) {
            throw $exception;
        };

        $messageRecorder
            ->expects($this->once())
            ->method('eraseMessages');

        try {
            $middleware->handle($this->dummyMessage(), $nextAlwaysFails);

            $this->fail('An exception should have been thrown');
        } catch (Exception $actualException) {
            $this->assertSame($exception, $actualException);
        }
    }

    /**
     * @param array $actuallyHandledMessages
     * @return \PHPUnit\Framework\MockObject\MockObject|MessageBus
     */
    private function messageBusSpy(array &$actuallyHandledMessages)
    {
        $messageBus = $this->createMock('SimpleBus\Message\Bus\MessageBus');

        $messageBus
            ->expects($this->any())
            ->method('handle')
            ->will(
                $this->returnCallback(
                    function ($message) use (&$actuallyHandledMessages) {
                        $actuallyHandledMessages[] = $message;
                    }
                )
            );

        return $messageBus;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|ContainsRecordedMessages
     */
    private function mockMessageRecorder()
    {
        return $this->createMock('SimpleBus\Message\Recorder\ContainsRecordedMessages');
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|MessageBus
     */
    private function dummyMessageBus()
    {
        return $this->createMock('SimpleBus\Message\Bus\MessageBus');
    }
}
