<?php

namespace SimpleBus\Message\Handler\Collection\Exception;

use LogicException;

class NoHandlerForMessageName extends \LogicException
{
    public function __construct($messageName)
    {
        parent::__construct(
            sprintf(
                'There is no message handler for message "%s"',
                $messageName
            )
        );
    }
}
