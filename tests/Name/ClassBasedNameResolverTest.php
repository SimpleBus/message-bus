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
    public function itReturnsTheFullClassNameAsTheUniqueNameOfAMessage()
    {
        $resolver = new ClassBasedNameResolver();
        $message = new DummyMessage();
        $this->assertSame(
            'SimpleBus\Message\Tests\Handler\Resolver\Fixtures\DummyMessage',
            $resolver->resolve($message)
        );
    }
}
