<?php

namespace SimpleBus\Message\Tests\Subscriber;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use SimpleBus\Message\Subscriber\Resolver\MessageSubscribersResolver;
use SimpleBus\Message\Tests\Fixtures\CallableSpy;
use stdClass;

class NotifiesMessageSubscribersMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function itNotifiesAllTheRelevantMessageSubscribers(): void
    {
        $message = $this->dummyMessage();

        $messageSubscriber1 = new CallableSpy();
        $messageSubscriber2 = new CallableSpy();

        $messageSubscribers = [
            $messageSubscriber1,
            $messageSubscriber2,
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
    public function itLogsEveryCallToASubscriber(): void
    {
        $message = $this->dummyMessage();

        $messageSubscriber1 = new CallableSpy();
        $messageSubscriber2 = new CallableSpy();

        $messageSubscribers = [
            $messageSubscriber1,
            $messageSubscriber2,
        ];

        $resolver = $this->mockMessageSubscribersResolver($message, $messageSubscribers);
        $logger = $this->createMock(LoggerInterface::class);
        $level = LogLevel::CRITICAL;

        $middleware = new NotifiesMessageSubscribersMiddleware($resolver, $logger, $level);

        $logger->expects($this->exactly(4))
            ->method('log')
            ->withConsecutive(
                [$level, 'Started notifying a subscriber'],
                [$level, 'Finished notifying a subscriber'],
                [$level, 'Started notifying a subscriber'],
                [$level, 'Finished notifying a subscriber']
            );

        $next = new CallableSpy();

        $middleware->handle($message, $next);

        $this->assertSame([$message], $next->receivedMessages());
        $this->assertSame([$message], $messageSubscriber1->receivedMessages());
        $this->assertSame([$message], $messageSubscriber2->receivedMessages());
    }

    /**
     * @param CallableSpy[] $messageSubscribers
     *
     * @return MessageSubscribersResolver|MockObject
     */
    private function mockMessageSubscribersResolver(object $message, array $messageSubscribers)
    {
        $resolver = $this->createMock(MessageSubscribersResolver::class);

        $resolver
            ->expects($this->any())
            ->method('resolve')
            ->with($this->identicalTo($message))
            ->will($this->returnValue($messageSubscribers));

        return $resolver;
    }

    private function dummyMessage(): stdClass
    {
        return new stdClass();
    }
}
