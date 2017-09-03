<?php

namespace SimpleBus\Message\Tests\Handler\Resolver;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\Name\MessageNameResolver;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;

class NameBasedMessageHandlerResolverTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_message_handler_from_the_handler_collection_by_its_name()
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
     * @return \PHPUnit_Framework_MockObject_MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }

    /**
     * @param $message
     * @param $messageName
     * @return \PHPUnit_Framework_MockObject_MockObject|MessageNameResolver
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
     * @return \PHPUnit_Framework_MockObject_MockObject|CallableMap
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
