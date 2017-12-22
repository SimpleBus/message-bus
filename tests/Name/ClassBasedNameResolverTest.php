<?php

namespace SimpleBus\Message\Tests\Name;

use SimpleBus\Message\Name\ClassBasedNameResolver;
use SimpleBus\Message\Tests\Handler\Resolver\Fixtures\DummyMessage;

class ClassBasedNameResolverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_returns_the_full_class_name_as_the_unique_name_of_a_message()
    {
        $resolver = new ClassBasedNameResolver();
        $message = new DummyMessage();
        $this->assertSame(
            'SimpleBus\Message\Tests\Handler\Resolver\Fixtures\DummyMessage',
            $resolver->resolve($message)
        );
    }
}
