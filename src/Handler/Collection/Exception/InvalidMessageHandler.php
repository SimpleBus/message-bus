<?php

namespace SimpleBus\Message\Handler\Collection\Exception;

use LogicException;

class InvalidMessageHandler extends \LogicException
{
    public function __construct($messageHandler)
    {
        parent::__construct(
            sprintf(
                'Expected an object of type "SimpleBus\Message\Handler\MessageHandler", got "%s"',
                $this->typeOf($messageHandler)
            )
        );
    }

    private function typeOf($messageHandler)
    {
        if (is_object($messageHandler)) {
            return get_class($messageHandler);
        }

        return gettype($messageHandler);
    }
}
