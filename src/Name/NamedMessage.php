<?php

namespace SimpleBus\Message\Name;

interface NamedMessage
{
    /**
     * The name of this particular type of message.
     */
    public static function messageName(): string;
}
