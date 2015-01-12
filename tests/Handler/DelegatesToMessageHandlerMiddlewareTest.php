<?php

namespace SimpleBus\Message\Tests\Handler;

use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Message;
use SimpleBus\Message\Handler\MessageHandler;
use SimpleBus\Message\Handler\Resolver\MessageHandlerResolver;
use SimpleBus\Message\Tests\Fixtures\NextCallableSpy;

class DelegatesToMessageHandlerMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_resolves_the_message_handler_and_lets_it_handle_the_message()
    {
        $message = $this->dummyMessage();
        $messageHandler = $this->mockMessageHandlerShouldHandle($message);
        $messageHandlerResolver = $this->mockMessageHandlerResolverShouldResolve($message, $messageHandler);

        $middleware = new DelegatesToMessageHandlerMiddleware($messageHandlerResolver);

        $next = new NextCallableSpy();

        $middleware->handle($message, $next);

        $this->assertSame(1, $next->hasBeenCalled());
        $this->assertSame([$message], $next->receivedMessages());
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
