<?php

namespace SimpleBus\Message;

interface NamedMessage extends Message
{
    /**
     * The name of this particular type of message.
     *
     * @return string
     */
    public function name();
}
