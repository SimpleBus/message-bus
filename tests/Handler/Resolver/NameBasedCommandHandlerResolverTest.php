<?php

namespace SimpleBus\Message\Tests\Handler\Resolver;

use PHPUnit_Framework_TestCase;
use SimpleBus\Message\Message;
use SimpleBus\Message\Handler\Collection\MessageHandlerCollection;
use SimpleBus\Message\Handler\MessageHandler;
use SimpleBus\Message\Handler\Resolver\Name\MessageNameResolver;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;

class NameBasedMessageHandlerResolverTest extends PHPUnit_Framework_TestCase
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
        $messageHandlerCollection = $this->stubMessageHandlerCollection([$messageName => $messageHandler]);

        $nameBasedHandlerResolver = new NameBasedMessageHandlerResolver(
            $messageNameResolver,
            $messageHandlerCollection
        );

        $this->assertSame($messageHandler, $nameBasedHandlerResolver->resolve($message));
    }

    private function dummyMessageHandler()
    {
        return $this->getMock('SimpleBus\Message\Handler\MessageHandler');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Message
     */
    private function dummyMessage()
    {
        return $this->getMock('SimpleBus\Message\Message');
    }

    /**
     * @param $message
     * @param $messageName
     * @return \PHPUnit_Framework_MockObject_MockObject|MessageNameResolver
     */
    private function stubMessageNameResolver($message, $messageName)
    {
        $messageNameResolver = $this->getMock('SimpleBus\Message\Handler\Resolver\Name\MessageNameResolver');

        $messageNameResolver
            ->expects($this->any())
            ->method('resolve')
            ->with($this->identicalTo($message))
            ->will($this->returnValue($messageName));

        return $messageNameResolver;
    }

    /**
     * @param MessageHandler[] $messageHandlersByMessageName
     * @return \PHPUnit_Framework_MockObject_MockObject|MessageHandlerCollection
     */
    private function stubMessageHandlerCollection(array $messageHandlersByMessageName)
    {
        $messageHandlerCollection = $this->getMock('SimpleBus\Message\Handler\Collection\MessageHandlerCollection');
        $messageHandlerCollection
            ->expects($this->any())
            ->method('getByMessageName')
            ->will(
                $this->returnCallback(
                    function ($messageName) use ($messageHandlersByMessageName) {
                        return $messageHandlersByMessageName[$messageName];
                    }
                )
            );

        return $messageHandlerCollection;
    }
}
