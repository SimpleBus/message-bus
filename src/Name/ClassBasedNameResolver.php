<?php

namespace SimpleBus\Message\Name;

use SimpleBus\Message\Message;

class ClassBasedNameResolver implements MessageNameResolver
{
    /**
     * The unique name of a message is assumed to be its fully qualified class name.
     *
     * {@inheritdoc}
     */
    public function resolve(Message $message)
    {
        return get_class($message);
    }
}
