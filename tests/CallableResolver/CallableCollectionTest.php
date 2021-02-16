<?php

namespace SimpleBus\Message\Tests\CallableResolver;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\CallableResolver\CallableResolver;

/**
 * @internal
 * @coversNothing
 */
class CallableCollectionTest extends TestCase
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
    public function itReturnsAnEmptyArrayIfNoCallablesAreDefined(): void
    {
        $collection = new CallableCollection([], $this->callableResolver);
        $this->assertSame([], $collection->filter('undefined_name'));
    }

    /**
     * @test
     */
    public function itReturnsManyResolvedCallablesForAGivenName(): void
    {
        $message1Callable1 = function () {
        };
        $message1Callable2 = function () {
        };
        $collection = new CallableCollection(
            [
                'message1' => [
                    $message1Callable1,
                    $message1Callable2,
                ],
                'message2' => [
                    function () {
                    },
                    function () {
                    },
                ],
            ],
            $this->callableResolver
        );

        $this->callableResolver
            ->expects($this->exactly(2))
            ->method('resolve')
            ->withConsecutive([$message1Callable1], [$message1Callable2])
            ->willReturnOnConsecutiveCalls($this->returnValue($message1Callable1), $this->returnValue($message1Callable2));

        $callables = $collection->filter('message1');

        $this->assertSame([$message1Callable1, $message1Callable2], $callables);
    }
}
