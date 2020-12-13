<?php

namespace SimpleBus\Message\Tests\Bus;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;

/**
 * @internal
 * @coversNothing
 */
class MessageBusSupportingMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function itLetsAllStackedMessageBusesHandleTheMessage()
    {
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
    public function itWorksWithNoMessageBuses()
    {
        $message = $this->dummyMessage();
        $messageBusStack = new MessageBusSupportingMiddleware([]);
        $messageBusStack->handle($message);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function itWorksWithOneMessageBus()
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
    public function itCanPrependMiddleware()
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
    public function itCanReturnAnArrayWithItsMiddlewares()
    {
        $stackedMessageBuses = [
            $this->createMock('SimpleBus\Message\Bus\Middleware\MessageBusMiddleware'),
            $this->createMock('SimpleBus\Message\Bus\Middleware\MessageBusMiddleware'),
        ];

        $messageBusStack = new MessageBusSupportingMiddleware($stackedMessageBuses);

        $this->assertEquals($stackedMessageBuses, $messageBusStack->getMiddlewares());
    }

    private function mockStackedMessageBus(&$actualMessageBusesCalled)
    {
        $messageBus = $this->createMock('SimpleBus\Message\Bus\Middleware\MessageBusMiddleware');

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

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }
}
