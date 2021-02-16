<?php

namespace SimpleBus\Message\Tests\Subscriber\Resolver;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\Name\MessageNameResolver;
use SimpleBus\Message\Subscriber\Resolver\NameBasedMessageSubscriberResolver;
use stdClass;

/**
 * @internal
 * @coversNothing
 */
class NameBasedMessageSubscriberResolverTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsMessageSubscribersFromTheHandlerCollectionByItsName(): void
    {
        $message = $this->dummyMessage();
        $messageName = 'message_name';
        $messageHandler = new stdClass();

        $messageNameResolver = $this->stubMessageNameResolver($message, $messageName);
        $messageHandlerCollection = $this->stubMessageSubscribersCollection([$messageName => [$messageHandler]]);

        $nameBasedHandlerResolver = new NameBasedMessageSubscriberResolver(
            $messageNameResolver,
            $messageHandlerCollection
        );

        $this->assertSame([$messageHandler], $nameBasedHandlerResolver->resolve($message));
    }

    private function dummyMessage(): stdClass
    {
        return new stdClass();
    }

    /**
     * @return MessageNameResolver|MockObject
     */
    private function stubMessageNameResolver(object $message, string $messageName)
    {
        $messageNameResolver = $this->createMock(MessageNameResolver::class);

        $messageNameResolver
            ->expects($this->any())
            ->method('resolve')
            ->with($this->identicalTo($message))
            ->will($this->returnValue($messageName));

        return $messageNameResolver;
    }

    /**
     * @param array<string, object[]> $messageSubscribersByMessageName
     *
     * @return CallableCollection|MockObject
     */
    private function stubMessageSubscribersCollection(array $messageSubscribersByMessageName)
    {
        $messageSubscribersCollection = $this->getMockBuilder(CallableCollection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $messageSubscribersCollection
            ->expects($this->any())
            ->method('filter')
            ->will(
                $this->returnCallback(
                    function ($messageName) use ($messageSubscribersByMessageName) {
                        return $messageSubscribersByMessageName[$messageName];
                    }
                )
            );

        return $messageSubscribersCollection;
    }
}
