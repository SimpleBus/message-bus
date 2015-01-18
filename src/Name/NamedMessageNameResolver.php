<?php

namespace SimpleBus\Message\Name;

use SimpleBus\Message\Name\Exception\CouldNotResolveMessageName;
use SimpleBus\Message\Message;

class NamedMessageNameResolver implements MessageNameResolver
{
    /**
     * {@inheritdoc}
     */
    public function resolve(Message $message)
    {
        if (!($message instanceof NamedMessage)) {
            throw CouldNotResolveMessageName::forMessage($message, 'Message should be an instance of NamedMessage');
        }

        $name = $message::messageName();

        if (!is_string($name) || empty($name)) {
            throw CouldNotResolveMessageName::forMessage(
                $message,
                sprintf(
                    'Static method "%s::messageName()" should return a non-empty string',
                    get_class($message)
                )
            );
        }

        return $name;
    }
}
