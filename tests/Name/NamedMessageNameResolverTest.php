<?php

namespace SimpleBus\Message\Tests\Name;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Name\NamedMessageNameResolver;
use SimpleBus\Message\Tests\Name\Fixtures\StubNamedMessage;
use stdClass;

class NamedMessageNameResolverTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsTheNameOfTheNamedMessage()
    {
        $messageName = 'message_name';
        StubNamedMessage::$name = $messageName;
        $message = new StubNamedMessage();

        $resolver = new NamedMessageNameResolver();

        $this->assertSame($messageName, $resolver->resolve($message));
    }

    /**
     * @test
     */
    public function itFailsWhenTheNameIsNotAString()
    {
        $notAString = new stdClass();
        StubNamedMessage::$name = $notAString;
        $message = new StubNamedMessage();

        $resolver = new NamedMessageNameResolver();

        $this->expectException('SimpleBus\Message\Name\Exception\CouldNotResolveMessageName');
        $resolver->resolve($message);
    }

    /**
     * @test
     */
    public function itFailsWhenTheNameIsAnEmptyString()
    {
        $emptyString = '';
        StubNamedMessage::$name = $emptyString;
        $message = new StubNamedMessage();

        $resolver = new NamedMessageNameResolver();

        $this->expectException('SimpleBus\Message\Name\Exception\CouldNotResolveMessageName');
        $resolver->resolve($message);
    }

    /**
     * @test
     */
    public function itFailsWhenTheMessageIsNotANamedMessage()
    {
        $resolver = new NamedMessageNameResolver();

        $this->expectException('SimpleBus\Message\Name\Exception\CouldNotResolveMessageName');
        $notANamedMessage = $this->dummyMessage();
        $resolver->resolve($notANamedMessage);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }
}
