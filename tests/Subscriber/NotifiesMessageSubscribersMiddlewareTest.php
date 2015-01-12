<?php

namespace SimpleBus\Message\Tests\Subscriber;

use SimpleBus\Message\Message;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use SimpleBus\Message\Tests\Fixtures\NextCallableSpy;

class NotifiesMessageSubscribersMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_notifies_all_the_relevant_message_subscribers()
    {
        $message = $this->dummyMessage();

        $messageSubscribers = [
            $this->mockMessageSubscriberShouldBeNotifiedOf($message),
            $this->mockMessageSubscriberShouldBeNotifiedOf($message),
            $this->mockMessageSubscriberShouldBeNotifiedOf($message),
        ];

        $resolver = $this->mockMessageSubscribersResolver($message, $messageSubscribers);
        $middleware = new NotifiesMessageSubscribersMiddleware($resolver);

        $next = new NextCallableSpy();

        $middleware->handle($message, $next);

        $this->assertSame(1, $next->hasBeenCalled());
        $this->assertSame([$message], $next->receivedMessages());
    }

    private function mockMessageSubscribersResolver(Message $message, array $messageSubscribers)
    {
        $resolver = $this->getMock('SimpleBus\Message\Subscriber\Resolver\MessageSubscribersResolver');

        $resolver
            ->expects($this->any())
            ->method('resolve')
            ->with($this->identicalTo($message))
            ->will($this->returnValue($messageSubscribers));

        return $resolver;
    }

    private function dummyMessage()
    {
        return $this->getMock('SimpleBus\Message\Message');
    }

    private function mockMessageSubscriberShouldBeNotifiedOf(Message $message)
    {
        $messageSubscriber = $this->getMock('SimpleBus\Message\Subscriber\MessageSubscriber');

        $messageSubscriber
            ->expects($this->once())
            ->method('notify')
            ->with($this->identicalTo($message));

        return $messageSubscriber;
    }
}
