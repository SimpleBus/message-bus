<?php

namespace SimpleBus\Message\Handler\Resolver\Name;

use SimpleBus\Message\Message;

class ClassBased implements MessageNameResolver
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
