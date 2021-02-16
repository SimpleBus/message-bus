<?php

namespace SimpleBus\Message\Tests\Handler\Resolver;

use Closure;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;
use SimpleBus\Message\Name\MessageNameResolver;
use stdClass;

/**
 * @internal
 * @coversNothing
 */
class NameBasedMessageHandlerResolverTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsAMessageHandlerFromTheHandlerCollectionByItsName(): void
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

    private function dummyMessageHandler(): Closure
    {
        return function () {
        };
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
     * @param callable[] $messageHandlersByMessageName
     *
     * @return CallableMap|MockObject
     */
    private function messageHandlerMap(array $messageHandlersByMessageName)
    {
        $messageHandlerMap = $this->getMockBuilder(CallableMap::class)
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
