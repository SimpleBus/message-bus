<?php

namespace SimpleBus\Message\Tests\Bus;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use stdClass;

/**
 * @internal
 * @coversNothing
 */
class MessageBusSupportingMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function itLetsAllStackedMessageBusesHandleTheMessage(): void
    {
        /** @var MessageBus[] $actualMessageBusesCalled */
        $actualMessageBusesCalled = [];

        $stackedMessageBuses = [
            $this->mockStackedMessageBus($actualMessageBusesCalled),
            $this->mockStackedMessageBus($actualMessageBusesCalled),
            $this->mockStackedMessageBus($actualMessageBusesCalled),
        ];

        $message = $this->dummyMessage();
        $messageBusStack = new MessageBusSupportingMiddleware($stackedMessageBuses);
        $messageBusStack->handle($message);

        $this->assertSame($stackedMessageBuses, $actualMessageBusesCalled);
    }

    /**
     * @test
     */
    public function itWorksWithNoMessageBuses(): void
    {
        $message = $this->dummyMessage();
        $messageBusStack = new MessageBusSupportingMiddleware([]);
        $messageBusStack->handle($message);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function itWorksWithOneMessageBus(): void
    {
        $actualMessageBusesCalled = [];

        $stackedMessageBuses = [
            $this->mockStackedMessageBus($actualMessageBusesCalled),
        ];

        $message = $this->dummyMessage();
        $messageBusStack = new MessageBusSupportingMiddleware($stackedMessageBuses);
        $messageBusStack->handle($message);

        $this->assertSame($stackedMessageBuses, $actualMessageBusesCalled);
    }

    /**
     * @test
     */
    public function itCanPrependMiddleware(): void
    {
        $actualMessageBusesCalled = [];

        $appended = $this->mockStackedMessageBus($actualMessageBusesCalled);
        $prepended = $this->mockStackedMessageBus($actualMessageBusesCalled);

        $message = $this->dummyMessage();
        $messageBusStack = new MessageBusSupportingMiddleware();
        $messageBusStack->appendMiddleware($appended);
        $messageBusStack->prependMiddleware($prepended);

        $messageBusStack->handle($message);

        $this->assertSame($prepended, $actualMessageBusesCalled[0]);
        $this->assertSame($appended, $actualMessageBusesCalled[1]);
    }

    /**
     * @test
     */
    public function itCanReturnAnArrayWithItsMiddlewares(): void
    {
        $stackedMessageBuses = [
            $this->createMock(MessageBusMiddleware::class),
            $this->createMock(MessageBusMiddleware::class),
        ];

        $messageBusStack = new MessageBusSupportingMiddleware($stackedMessageBuses);

        $this->assertEquals($stackedMessageBuses, $messageBusStack->getMiddlewares());
    }

    /**
     * @param MessageBus[] $actualMessageBusesCalled
     *
     * @return MessageBusMiddleware|MockObject
     */
    private function mockStackedMessageBus(&$actualMessageBusesCalled)
    {
        $messageBus = $this->createMock(MessageBusMiddleware::class);

        $messageBus
            ->expects($this->once())
            ->method('handle')
            ->will(
                $this->returnCallback(
                    function ($message, callable $next) use (&$actualMessageBusesCalled, $messageBus) {
                        $actualMessageBusesCalled[] = $messageBus;
                        $next($message);
                    }
                )
            );

        return $messageBus;
    }

    private function dummyMessage(): stdClass
    {
        return new stdClass();
    }
}
