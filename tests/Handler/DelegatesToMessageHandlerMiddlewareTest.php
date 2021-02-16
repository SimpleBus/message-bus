<?php

namespace SimpleBus\Message\Tests\Handler;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Handler\Resolver\MessageHandlerResolver;
use SimpleBus\Message\Tests\Fixtures\CallableSpy;
use stdClass;

/**
 * @internal
 * @coversNothing
 */
class DelegatesToMessageHandlerMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function itResolvesTheMessageHandlerAndLetsItHandleTheMessage(): void
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

    private function dummyMessage(): stdClass
    {
        return new stdClass();
    }

    /**
     * @return MessageHandlerResolver|MockObject
     */
    private function mockMessageHandlerResolverShouldResolve(object $message, callable $resolvedMessageHandler)
    {
        $messageHandlerResolver = $this->createMock(MessageHandlerResolver::class);

        $messageHandlerResolver
            ->expects($this->once())
            ->method('resolve')
            ->with($this->identicalTo($message))
            ->will($this->returnValue($resolvedMessageHandler));

        return $messageHandlerResolver;
    }
}
