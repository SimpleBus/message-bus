<?php

namespace SimpleBus\Message\Tests\Subscriber;

use Psr\Log\LogLevel;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use SimpleBus\Message\Subscriber\Resolver\MessageSubscribersResolver;
use SimpleBus\Message\Tests\Fixtures\CallableSpy;

class NotifiesMessageSubscribersMiddlewareTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_notifies_all_the_relevant_message_subscribers()
    {
        $message = $this->dummyMessage();

        $messageSubscriber1 = new CallableSpy();
        $messageSubscriber2 = new CallableSpy();

        $messageSubscribers = [
            $messageSubscriber1,
            $messageSubscriber2
        ];

        $resolver = $this->mockMessageSubscribersResolver($message, $messageSubscribers);
        $middleware = new NotifiesMessageSubscribersMiddleware($resolver);

        $next = new CallableSpy();

        $middleware->handle($message, $next);

        $this->assertSame([$message], $next->receivedMessages());
        $this->assertSame([$message], $messageSubscriber1->receivedMessages());
        $this->assertSame([$message], $messageSubscriber2->receivedMessages());
    }

    /**
     * @test
     */
    public function it_logs_every_call_to_a_subscriber()
    {
        $message = $this->dummyMessage();

        $messageSubscriber1 = new CallableSpy();
        $messageSubscriber2 = new CallableSpy();

        $messageSubscribers = [
            $messageSubscriber1,
            $messageSubscriber2
        ];

        $resolver = $this->mockMessageSubscribersResolver($message, $messageSubscribers);
        $logger = $this->createMock('Psr\Log\LoggerInterface');
        $level = LogLevel::CRITICAL;

        $middleware = new NotifiesMessageSubscribersMiddleware($resolver, $logger, $level);

        $logger->expects($this->at(0))
            ->method('log')
            ->with($level, 'Started notifying a subscriber');

        $logger->expects($this->at(1))
            ->method('log')
            ->with($level, 'Finished notifying a subscriber');

        $logger->expects($this->at(2))
            ->method('log')
            ->with($level, 'Started notifying a subscriber');

        $logger->expects($this->at(3))
            ->method('log')
            ->with($level, 'Finished notifying a subscriber');

        $next = new CallableSpy();

        $middleware->handle($message, $next);

        $this->assertSame([$message], $next->receivedMessages());
        $this->assertSame([$message], $messageSubscriber1->receivedMessages());
        $this->assertSame([$message], $messageSubscriber2->receivedMessages());
    }

    /**
     * @param object $message
     * @param array $messageSubscribers
     * @return \PHPUnit_Framework_MockObject_MockObject|MessageSubscribersResolver
     */
    private function mockMessageSubscribersResolver($message, array $messageSubscribers)
    {
        $resolver = $this->createMock('SimpleBus\Message\Subscriber\Resolver\MessageSubscribersResolver');

        $resolver
            ->expects($this->any())
            ->method('resolve')
            ->with($this->identicalTo($message))
            ->will($this->returnValue($messageSubscribers));

        return $resolver;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }
}
