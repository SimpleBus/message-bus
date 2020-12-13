<?php

namespace SimpleBus\Message\Tests\Subscriber\Resolver;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\Name\MessageNameResolver;
use SimpleBus\Message\Subscriber\Resolver\NameBasedMessageSubscriberResolver;

class NameBasedMessageSubscriberResolverTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsMessageSubscribersFromTheHandlerCollectionByItsName()
    {
        $message = $this->dummyMessage();
        $messageName = 'message_name';
        $messageHandler = $this->dummyMessageHandler();

        $messageNameResolver = $this->stubMessageNameResolver($message, $messageName);
        $messageHandlerCollection = $this->stubMessageSubscribersCollection([$messageName => $messageHandler]);

        $nameBasedHandlerResolver = new NameBasedMessageSubscriberResolver(
            $messageNameResolver,
            $messageHandlerCollection
        );

        $this->assertSame($messageHandler, $nameBasedHandlerResolver->resolve($message));
    }

    private function dummyMessageHandler()
    {
        return $this->getMockBuilder('SimpleBus\Message\Handler\MessageHandler')->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }

    /**
     * @param $message
     * @param $messageName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|MessageNameResolver
     */
    private function stubMessageNameResolver($message, $messageName)
    {
        $messageNameResolver = $this->createMock('SimpleBus\Message\Name\MessageNameResolver');

        $messageNameResolver
            ->expects($this->any())
            ->method('resolve')
            ->with($this->identicalTo($message))
            ->will($this->returnValue($messageName));

        return $messageNameResolver;
    }

    /**
     * @param callable[] $messageSubscribersByMessageName
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|CallableCollection
     */
    private function stubMessageSubscribersCollection(array $messageSubscribersByMessageName)
    {
        $messageSubscribersCollection = $this->getMockBuilder('SimpleBus\Message\CallableResolver\CallableCollection')
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
