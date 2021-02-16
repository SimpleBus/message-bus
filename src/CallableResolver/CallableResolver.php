<?php

namespace SimpleBus\Message\CallableResolver;

use SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable;

interface CallableResolver
{
    /**
     * @param callable|mixed|object|string $maybeCallable
     *
     * @throws CouldNotResolveCallable
     */
    public function resolve($maybeCallable): callable;
}
