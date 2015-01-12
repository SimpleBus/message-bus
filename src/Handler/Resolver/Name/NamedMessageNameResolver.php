<?php

namespace SimpleBus\Message\Handler\Resolver\Name;

use SimpleBus\Message\Handler\Resolver\Name\Exception\CouldNotResolveMessageName;
use SimpleBus\Message\Message;
use SimpleBus\Message\NamedMessage;

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

        $name = $message->name();

        if (!is_string($name) || empty($name)) {
            throw CouldNotResolveMessageName::forMessage(
                $message,
                sprintf(
                    'Method "%s::name()" should return a non-empty string',
                    get_class($message)
                )
            );
        }

        return $name;
    }
}
