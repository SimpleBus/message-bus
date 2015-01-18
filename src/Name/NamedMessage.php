<?php

namespace SimpleBus\Message\Name;

use SimpleBus\Message\Message;

interface NamedMessage extends Message
{
    /**
     * The name of this particular type of message.
     *
     * @return string
     */
    public static function messageName();
}
