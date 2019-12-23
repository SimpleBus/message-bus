<?php

namespace SimpleBus\Message\Tests\CallableResolver;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\CallableResolver\CallableResolver;

class CallableCollectionTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|CallableResolver
     */
    private $callableResolver;

    protected function setUp(): void
    {
        $this->callableResolver = $this->createMock('SimpleBus\Message\CallableResolver\CallableResolver');
    }

    /**
     * @test
     */
    public function it_returns_an_empty_array_if_no_callables_are_defined()
    {
        $collection = new CallableCollection([], $this->callableResolver);
        $this->assertSame([], $collection->filter('undefined_name'));
    }

    /**
     * @test
     */
    public function it_returns_many_resolved_callables_for_a_given_name()
    {
        $message1Callable1 = function () {
        };
        $message1Callable2 = function () {
        };
        $collection = new CallableCollection(
            [
                'message1' => [
                    $message1Callable1,
                    $message1Callable2
                ],
                'message2' => [
                    function () {},
                    function () {}
                ]
            ],
            $this->callableResolver
        );

        $this->callableResolverShouldResolve([$message1Callable1, $message1Callable2]);

        $callables = $collection->filter('message1');

        $this->assertSame([$message1Callable1, $message1Callable2], $callables);
    }

    private function callableResolverShouldResolve(array $callables)
    {
        foreach ($callables as $index => $callable) {
            $this->callableResolver
                ->expects($this->at($index))
                ->method('resolve')
                ->with($this->identicalTo($callable))
                ->will($this->returnValue($callable));
        }
    }
}
