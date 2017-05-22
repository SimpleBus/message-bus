<?php

namespace SimpleBus\Message\Bus\Middleware;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SpooledMessageBusMiddleware implements MessageBusMiddleware
{
    private $spool = [];

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        $this->spool[] = function () use ($message, $next) {
            $next($message);
        };
    }

    /**
     * Flush the spool.
     */
    public function flush()
    {
        while ($callback = array_shift($this->spool)) {
            call_user_func($callback);
        }
    }
}
