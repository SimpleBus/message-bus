<?php

namespace SimpleBus\Message\CallableResolver\Exception;

use LogicException;

class CouldNotResolveCallable extends LogicException
{
    /**
     * @param mixed $value
     */
    public static function createFor($value): self
    {
        return new self(
            sprintf(
                '%s could not be resolved to a valid callable',
                static::printValue($value)
            )
        );
    }

    /**
     * @param mixed $value
     */
    private static function printValue($value): string
    {
        return str_replace('  ', '', str_replace("\n", '', print_r(self::convertValue($value), true)));
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    private static function convertValue($value)
    {
        if (is_array($value)) {
            return array_map(function ($value) {
                return self::convertObject($value);
            }, $value);
        }

        return self::convertObject($value);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    private static function convertObject($value)
    {
        if (is_object($value)) {
            return get_class($value);
        }

        return $value;
    }
}
