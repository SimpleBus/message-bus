<?php

namespace SimpleBus\Message\CallableResolver\Exception;

class CouldNotResolveCallable extends \LogicException
{
    public static function createFor($value)
    {
        return new self(
            sprintf(
                '%s could not be resolved to a valid callable',
                static::printValue($value)
            )
        );
    }

    private static function printValue($value)
    {
        return str_replace('  ', '', str_replace("\n", '', print_r($value, true)));
    }
}
