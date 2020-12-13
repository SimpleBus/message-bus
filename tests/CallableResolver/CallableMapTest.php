<?php

namespace SimpleBus\Message\Tests\CallableResolver;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\CallableResolver;

/**
 * @internal
 * @coversNothing
 */
class CallableMapTest extends TestCase
{
    /**
     * @var CallableResolver|\PHPUnit\Framework\MockObject\MockObject
     */
    private $callableResolver;
    private $map;

    protected function setUp(): void
    {
        $this->callableResolver = $this->createMock('SimpleBus\Message\CallableResolver\CallableResolver');
    }

    /**
     * @test
     */
    public function itFailsIfNoCallableIsDefinedForAGivenName()
    {
        $map = new CallableMap([], $this->callableResolver);

        $this->expectException('SimpleBus\Message\CallableResolver\Exception\UndefinedCallable');
        $map->get('undefined_name');
    }

    /**
     * @test
     */
    public function itReturnsManyResolvedCallablesForAGivenName()
    {
        $message1Callable = function () {
        };
        $this->map = new CallableMap(
            [
                'message1' => $message1Callable,
                'message2' => function () {
                },
            ],
            $this->callableResolver
        );

        $this->callableResolverShouldResolve($message1Callable);

        $callable = $this->map->get('message1');

        $this->assertSame($message1Callable, $callable);
    }

    private function callableResolverShouldResolve($callable)
    {
        $this->callableResolver
            ->expects($this->once())
            ->method('resolve')
            ->with($this->identicalTo($callable))
            ->will($this->returnValue($callable));
    }
}
