<?php

namespace SimpleBus\Message\Tests\Bus\Fixtures;

use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class StubMessageBusMiddleware implements MessageBusMiddleware
{
    /**
     * @var callable
     */
    private $handler;

    public function __construct(callable $handler)
    {
        $this->handler = $handler;
    }

    public function handle($message, callable $next)
    {
        call_user_func($this->handler, $message);

        $next();
    }
}
