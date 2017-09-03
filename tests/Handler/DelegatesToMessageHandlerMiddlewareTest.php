<?php

namespace SimpleBus\Message\Tests\Handler;

use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Handler\Resolver\MessageHandlerResolver;
use SimpleBus\Message\Tests\Fixtures\CallableSpy;

class DelegatesToMessageHandlerMiddlewareTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_resolves_the_message_handler_and_lets_it_handle_the_message()
    {
        $message = $this->dummyMessage();
        $messageHandler = new CallableSpy();
        $nextMiddleware = new CallableSpy();
        $messageHandlerResolver = $this->mockMessageHandlerResolverShouldResolve($message, $messageHandler);

        $middleware = new DelegatesToMessageHandlerMiddleware($messageHandlerResolver);

        $middleware->handle($message, $nextMiddleware);

        $this->assertSame([$message], $nextMiddleware->receivedMessages());
        $this->assertSame([$message], $messageHandler->receivedMessages());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }

    /**
     * @param object $message
     * @param callable $resolvedMessageHandler
     * @return \PHPUnit_Framework_MockObject_MockObject|MessageHandlerResolver
     */
    private function mockMessageHandlerResolverShouldResolve($message, $resolvedMessageHandler)
    {
        $messageHandlerResolver = $this->createMock('SimpleBus\Message\Handler\Resolver\MessageHandlerResolver');

        $messageHandlerResolver
            ->expects($this->once())
            ->method('resolve')
            ->with($this->identicalTo($message))
            ->will($this->returnValue($resolvedMessageHandler));

        return $messageHandlerResolver;
    }
}
