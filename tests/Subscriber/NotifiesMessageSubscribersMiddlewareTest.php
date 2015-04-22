<?php

namespace SimpleBus\Message\Tests\Subscriber;

use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use SimpleBus\Message\Subscriber\Resolver\MessageSubscribersResolver;
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

    /**
     * @param object $message
     * @param array $messageSubscribers
     * @return \PHPUnit_Framework_MockObject_MockObject|MessageSubscribersResolver
     */
    private function mockMessageSubscribersResolver($message, array $messageSubscribers)
    {
        $resolver = $this->getMock('SimpleBus\Message\Subscriber\Resolver\MessageSubscribersResolver');

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

    private function mockMessageSubscriberShouldBeNotifiedOf($message)
    {
        $messageSubscriber = $this->getMock('SimpleBus\Message\Subscriber\MessageSubscriber');

        $messageSubscriber
            ->expects($this->once())
            ->method('notify')
            ->with($this->identicalTo($message));

        return $messageSubscriber;
    }
}
