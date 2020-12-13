<?php

namespace SimpleBus\Message\Tests\Handler;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Handler\Resolver\MessageHandlerResolver;
use SimpleBus\Message\Tests\Fixtures\CallableSpy;

/**
 * @internal
 * @coversNothing
 */
class DelegatesToMessageHandlerMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function itResolvesTheMessageHandlerAndLetsItHandleTheMessage()
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }

    /**
     * @param object   $message
     * @param callable $resolvedMessageHandler
     *
     * @return MessageHandlerResolver|\PHPUnit\Framework\MockObject\MockObject
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
