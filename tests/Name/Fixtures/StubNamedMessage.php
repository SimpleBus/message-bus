<?php

namespace SimpleBus\Message\Tests\Name\Fixtures;

use SimpleBus\Message\Name\NamedMessage;

class StubNamedMessage implements NamedMessage
{
    public static string $name;

    public static function messageName(): string
    {
        return self::$name;
    }
}
