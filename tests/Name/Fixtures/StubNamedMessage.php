<?php

namespace SimpleBus\Message\Tests\Name\Fixtures;

use SimpleBus\Message\Name\NamedMessage;

class StubNamedMessage implements NamedMessage
{
    public static $name;

    public static function messageName()
    {
        return self::$name;
    }
}
