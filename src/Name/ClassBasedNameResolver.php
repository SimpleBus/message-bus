<?php

namespace SimpleBus\Message\Name;

class ClassBasedNameResolver implements MessageNameResolver
{
    /**
     * The unique name of a message is assumed to be its fully qualified class name.
     */
    public function resolve(object $message): string
    {
        return get_class($message);
    }
}
