<?php

namespace SimpleBus\Message\Tests\CallableResolver;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\CallableResolver;

class CallableMapTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CallableResolver
     */
    private $callableResolver;
    private $map;

    protected function setUp()
    {
        $this->callableResolver = $this->createMock('SimpleBus\Message\CallableResolver\CallableResolver');
    }

    /**
     * @test
     */
    public function it_fails_if_no_callable_is_defined_for_a_given_name()
    {
        $map = new CallableMap([], $this->callableResolver);

        $this->expectException('SimpleBus\Message\CallableResolver\Exception\UndefinedCallable');
        $map->get('undefined_name');
    }

    /**
     * @test
     */
    public function it_returns_many_resolved_callables_for_a_given_name()
    {
        $message1Callable = function () {
        };
        $this->map = new CallableMap(
            [
                'message1' => $message1Callable,
                'message2' => function () {
                }
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
