<?php

namespace SimpleBus\Message\Name;

use SimpleBus\Message\Message;

interface MessageNameResolver
{
    /**
     * Resolve the unique name of a message, e.g. the full class name
     *
     * @param Message $message
     * @return string
     */
    public function resolve(Message $message);
}
