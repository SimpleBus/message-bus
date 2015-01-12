<?php

namespace SimpleBus\Message\Tests\Bus;

use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\Message;

class MessageBusSupportingMiddlewareTest extends \PHPUnit_Framework_TestCase
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

    private function mockStackedMessageBus(&$actualMessageBusesCalled)
    {
        $messageBus = $this->getMock('SimpleBus\Message\Bus\Middleware\MessageBusMiddleware');

        $messageBus
            ->expects($this->once())
            ->method('handle')
            ->will(
                $this->returnCallback(
                    function (Message $message, callable $next) use (&$actualMessageBusesCalled, $messageBus) {
                        $actualMessageBusesCalled[] = $messageBus;
                        $next($message);
                    }
                )
            );

        return $messageBus;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Message
     */
    private function dummyMessage()
    {
        return $this->getMock('SimpleBus\Message\Message');
    }
}
