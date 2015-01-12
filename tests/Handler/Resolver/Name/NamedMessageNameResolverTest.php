<?php

namespace SimpleBus\Message\Tests\Handler\Resolver\Name;

use SimpleBus\Message\Handler\Resolver\Name\NamedMessageNameResolver;
use SimpleBus\Message\Tests\Handler\Resolver\Name\Fixtures\StubNamedMessage;
use stdClass;

class NamedMessageNameResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_returns_the_name_of_the_named_message()
    {
        $messageName = 'message_name';
        $message = new StubNamedMessage($messageName);

        $resolver = new NamedMessageNameResolver();

        $this->assertSame($messageName, $resolver->resolve($message));
    }

    /**
     * @test
     */
    public function it_fails_when_the_name_is_not_a_string()
    {
        $notAString = new stdClass();
        $message = new StubNamedMessage($notAString);

        $resolver = new NamedMessageNameResolver();

        $this->setExpectedException('SimpleBus\Message\Handler\Resolver\Name\Exception\CouldNotResolveMessageName');
        $resolver->resolve($message);
    }

    /**
     * @test
     */
    public function it_fails_when_the_name_is_an_empty_string()
    {
        $emptyString = '';
        $message = new StubNamedMessage($emptyString);

        $resolver = new NamedMessageNameResolver();

        $this->setExpectedException('SimpleBus\Message\Handler\Resolver\Name\Exception\CouldNotResolveMessageName');
        $resolver->resolve($message);
    }

    /**
     * @test
     */
    public function it_fails_when_the_message_is_not_a_named_message()
    {
        $resolver = new NamedMessageNameResolver();

        $this->setExpectedException('SimpleBus\Message\Handler\Resolver\Name\Exception\CouldNotResolveMessageName');
        $notANamedMessage = $this->dummyMessage();
        $resolver->resolve($notANamedMessage);
    }

    private function dummyMessage()
    {
        return $this->getMock('SimpleBus\Message\Message');
    }
}
