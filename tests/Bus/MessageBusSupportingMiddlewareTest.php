<?php

namespace SimpleBus\Message\Tests\Bus;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;

class MessageBusSupportingMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function it_lets_all_stacked_message_buses_handle_the_message()
    {
        $actualMessageBusesCalled = [];

        $stackedMessageBuses = [
            $this->mockStackedMessageBus($actualMessageBusesCalled),
            $this->mockStackedMessageBus($actualMessageBusesCalled),
            $this->mockStackedMessageBus($actualMessageBusesCalled)
        ];

        $message = $this->dummyMessage();
        $messageBusStack = new MessageBusSupportingMiddleware($stackedMessageBuses);
        $messageBusStack->handle($message);

        $this->assertSame($stackedMessageBuses, $actualMessageBusesCalled);
    }

    /**
     * @test
     */
    public function it_works_with_no_message_buses()
    {
        $message = $this->dummyMessage();
        $messageBusStack = new MessageBusSupportingMiddleware([]);
        $messageBusStack->handle($message);
    }

    /**
     * @test
     */
    public function it_works_with_one_message_bus()
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
    public function it_can_prepend_middleware()
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
    public function it_can_return_an_array_with_its_middlewares()
    {
        $stackedMessageBuses = [
            $this->createMock('SimpleBus\Message\Bus\Middleware\MessageBusMiddleware'),
            $this->createMock('SimpleBus\Message\Bus\Middleware\MessageBusMiddleware')
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }
}
