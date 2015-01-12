<?php

namespace SimpleBus\Message\Handler\Resolver\Name\Exception;

use LogicException;
use SimpleBus\Message\Message;

class CouldNotResolveMessageName extends \LogicException
{
    public static function forMessage(Message $message, $exceptionMessage)
    {
        return new self(
            sprintf(
                'Could not determine the name of a message of type "%s": %s',
                get_class($message),
                $exceptionMessage
            )
        );
    }
}
