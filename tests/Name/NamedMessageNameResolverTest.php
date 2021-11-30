<?php

namespace SimpleBus\Message\Tests\Name;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Name\Exception\CouldNotResolveMessageName;
use SimpleBus\Message\Name\NamedMessageNameResolver;
use SimpleBus\Message\Tests\Name\Fixtures\StubNamedMessage;
use stdClass;

class NamedMessageNameResolverTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsTheNameOfTheNamedMessage(): void
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
    public function itFailsWhenTheNameIsAnEmptyString(): void
    {
        $emptyString = '';
        StubNamedMessage::$name = $emptyString;
        $message = new StubNamedMessage();

        $resolver = new NamedMessageNameResolver();

        $this->expectException(CouldNotResolveMessageName::class);
        $resolver->resolve($message);
    }

    /**
     * @test
     */
    public function itFailsWhenTheMessageIsNotANamedMessage(): void
    {
        $resolver = new NamedMessageNameResolver();

        $this->expectException(CouldNotResolveMessageName::class);
        $notANamedMessage = $this->dummyMessage();
        $resolver->resolve($notANamedMessage);
    }

    private function dummyMessage(): stdClass
    {
        return new stdClass();
    }
}
