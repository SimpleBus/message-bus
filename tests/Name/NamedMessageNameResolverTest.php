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
    public function it_returns_the_name_of_the_named_message()
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
    public function it_fails_when_the_name_is_not_a_string()
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
    public function it_fails_when_the_name_is_an_empty_string()
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
    public function it_fails_when_the_message_is_not_a_named_message()
    {
        $resolver = new NamedMessageNameResolver();

        $this->expectException('SimpleBus\Message\Name\Exception\CouldNotResolveMessageName');
        $notANamedMessage = $this->dummyMessage();
        $resolver->resolve($notANamedMessage);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\stdClass
     */
    private function dummyMessage()
    {
        return new \stdClass();
    }
}
