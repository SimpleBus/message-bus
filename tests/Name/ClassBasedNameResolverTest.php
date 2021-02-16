<?php

namespace SimpleBus\Message\Tests\Name;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use SimpleBus\Message\Tests\Handler\Resolver\Fixtures\DummyMessage;

/**
 * @internal
 * @coversNothing
 */
class ClassBasedNameResolverTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsTheFullClassNameAsTheUniqueNameOfAMessage(): void
    {
        $resolver = new ClassBasedNameResolver();
        $message = new DummyMessage();
        $this->assertSame(
            DummyMessage::class,
            $resolver->resolve($message)
        );
    }
}
