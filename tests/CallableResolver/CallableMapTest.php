<?php

namespace SimpleBus\Message\Tests\CallableResolver;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\CallableResolver;
use SimpleBus\Message\CallableResolver\Exception\UndefinedCallable;

/**
 * @internal
 * @coversNothing
 */
class CallableMapTest extends TestCase
{
    /**
     * @var CallableResolver|MockObject
     */
    private $callableResolver;

    protected function setUp(): void
    {
        $this->callableResolver = $this->createMock(CallableResolver::class);
    }

    /**
     * @test
     */
    public function itFailsIfNoCallableIsDefinedForAGivenName(): void
    {
        $map = new CallableMap([], $this->callableResolver);

        $this->expectException(UndefinedCallable::class);
        $map->get('undefined_name');
    }

    /**
     * @test
     */
    public function itReturnsManyResolvedCallablesForAGivenName(): void
    {
        $message1Callable = function () {
        };
        $map = new CallableMap(
            [
                'message1' => $message1Callable,
                'message2' => function () {
                },
            ],
            $this->callableResolver
        );

        $this->callableResolverShouldResolve($message1Callable);

        $callable = $map->get('message1');

        $this->assertSame($message1Callable, $callable);
    }

    private function callableResolverShouldResolve(callable $callable): void
    {
        $this->callableResolver
            ->expects($this->once())
            ->method('resolve')
            ->with($this->identicalTo($callable))
            ->will($this->returnValue($callable));
    }
}
