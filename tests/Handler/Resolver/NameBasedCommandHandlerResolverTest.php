<?php

namespace SimpleBus\Message\Tests\Handler\Resolver;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;
use SimpleBus\Message\Name\MessageNameResolver;

/**
 * @internal
 * @coversNothing
 */
class NameBasedMessageHandlerResolverTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsAMessageHandlerFromTheHandlerCollectionByItsName()
    {
        $message = $this->dummyMessage();
        $messageName = 'message_name';
        $messageHandler = $this->dummyMessageHandler();

        $messageNameResolver = $this->stubMessageNameResolver($message, $messageName);
        $messageHandlerCollection = $this->messageHandlerMap([$messageName => $messageHandler]);

        $nameBasedHandlerResolver = new NameBasedMessageHandlerResolver(
            $messageNameResolver,
            $messageHandlerCollection
        );

        $this->assertSame($messageHandler, $nameBasedHandlerResolver->resolve($message));
    }

    private function dummyMessageHandler()
    {
        return function () {
        };
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
     * @return MessageNameResolver|\PHPUnit\Framework\MockObject\MockObject
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
     * @param callable[] $messageHandlersByMessageName
     *
     * @return CallableMap|\PHPUnit\Framework\MockObject\MockObject
     */
    private function messageHandlerMap(array $messageHandlersByMessageName)
    {
        $messageHandlerMap = $this->getMockBuilder('SimpleBus\Message\CallableResolver\CallableMap')
            ->disableOriginalConstructor()
            ->getMock();
        $messageHandlerMap
            ->expects($this->any())
            ->method('get')
            ->will(
                $this->returnCallback(
                    function ($messageName) use ($messageHandlersByMessageName) {
                        return $messageHandlersByMessageName[$messageName];
                    }
                )
            );

        return $messageHandlerMap;
    }
}
