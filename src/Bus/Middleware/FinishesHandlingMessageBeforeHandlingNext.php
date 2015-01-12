<?php

namespace SimpleBus\Message\Bus\Middleware;

use SimpleBus\Message\Message;

class FinishesHandlingMessageBeforeHandlingNext implements MessageBusMiddleware
{
    /**
     * @var array
     */
    private $queue = [];

    /**
     * @var bool
     */
    private $isHandling = false;

    /**
     * Completely finishes handling the current message, before allowing other middlewares to start handling new
     * messages.
     *
     * {@inheritdoc}
     */
    public function handle(Message $message, callable $next)
    {
        $this->queue[] = $message;

        if (!$this->isHandling) {
            $this->isHandling = true;

            while ($message = array_shift($this->queue)) {
                $next($message);
            }

            $this->isHandling = false;
        }
    }
}
