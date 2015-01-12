<?php

namespace SimpleBus\Message\Tests\Handler\Resolver\Name;

use SimpleBus\Message\Handler\Resolver\Name\ClassBased;
use SimpleBus\Message\Tests\Handler\Resolver\Fixtures\DummyMessage;

class ClassBasedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_returns_the_full_class_name_as_the_unique_name_of_a_message()
    {
        $resolver = new ClassBased();
        $message = new DummyMessage();
        $this->assertSame(
            'SimpleBus\Message\Tests\Handler\Resolver\Fixtures\DummyMessage',
            $resolver->resolve($message)
        );
    }
}
