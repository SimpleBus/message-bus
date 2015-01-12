<?php

namespace SimpleBus\Message\Tests\Bus;

use SimpleBus\Message\Bus\Middleware\DelegatesToMessageHandlers;
use SimpleBus\Message\Message;
use SimpleBus\Message\Handler\MessageHandler;
use SimpleBus\Message\Handler\Resolver\MessageHandlerResolver;

class DelegatesToMessageHandlersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_resolves_the_message_handler_and_lets_it_handle_the_message()
    {
        $message = $this->dummyMessage();
        $messageHandler = $this->mockMessageHandlerShouldHandle($message);
        $messageHandlerResolver = $this->mockMessageHandlerResolverShouldResolve($message, $messageHandler);

        $messageBus = new DelegatesToMessageHandlers($messageHandlerResolver);

        $nextIsCalled = false;
        $next = function(Message $actualMessage) use (&$nextIsCalled, $message) {
            $this->assertSame($message, $actualMessage);
            $nextIsCalled = true;
        };

        $messageBus->handle($message, $next);

        $this->assertTrue($nextIsCalled);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Message
     */
    private function dummyMessage()
    {
        return $this->getMock('SimpleBus\Message\Message');
    }

    /**
     * @param Message $message
     * @return \PHPUnit_Framework_MockObject_MockObject|MessageHandler
     */
    private function mockMessageHandlerShouldHandle(Message $message)
    {
        $messageHandler = $this->getMock('SimpleBus\Message\Handler\MessageHandler');

        $messageHandler
            ->expects($this->once())
            ->method('handle')
            ->with($this->identicalTo($message));

        return $messageHandler;
    }

    /**
     * @param Message $message
     * @param MessageHandler $resolvedMessageHandler
     * @return \PHPUnit_Framework_MockObject_MockObject|MessageHandlerResolver
     */
    private function mockMessageHandlerResolverShouldResolve(Message $message, MessageHandler $resolvedMessageHandler)
    {
        $messageHandlerResolver = $this->getMock('SimpleBus\Message\Handler\Resolver\MessageHandlerResolver');

        $messageHandlerResolver
            ->expects($this->once())
            ->method('resolve')
            ->with($this->identicalTo($message))
            ->will($this->returnValue($resolvedMessageHandler));

        return $messageHandlerResolver;
    }
}
