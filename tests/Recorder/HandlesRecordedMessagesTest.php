<?php

namespace SimpleBus\Message\Tests\Recorder;

use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Message\Recorder\HandlesRecordedMessages;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;

class HandlesRecordedMessagesTest extends \PHPUnit_Framework_TestCase
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
        $middleware = new HandlesRecordedMessages(
            $messageRecorder,
            $messageBus
        );

        $middleware->dispatch();
    }

    /**
     * @param array $actuallyHandledMessages
     * @return \PHPUnit_Framework_MockObject_MockObject|MessageBus
     */
    private function messageBusSpy(array &$actuallyHandledMessages)
    {
        $messageBus = $this->getMock('SimpleBus\Message\Bus\MessageBus');

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
     * @return \PHPUnit_Framework_MockObject_MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ContainsRecordedMessages
     */
    private function mockMessageRecorder()
    {
        return $this->getMock('SimpleBus\Message\Recorder\ContainsRecordedMessages');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|MessageBus
     */
    private function dummyMessageBus()
    {
        return $this->getMock('SimpleBus\Message\Bus\MessageBus');
    }
}
