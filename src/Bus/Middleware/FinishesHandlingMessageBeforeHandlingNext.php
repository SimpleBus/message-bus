<?php

namespace SimpleBus\Message\Bus\Middleware;

use Throwable;

class FinishesHandlingMessageBeforeHandlingNext implements MessageBusMiddleware
{
    /**
     * @var object[]
     */
    private array $queue = [];

    private bool $isHandling = false;

    /**
     * Completely finishes handling the current message, before allowing other middlewares to start handling new messages.
     */
    public function handle(object $message, callable $next): void
    {
        $this->queue[] = $message;

        if (!$this->isHandling) {
            $this->isHandling = true;

            while ($message = array_shift($this->queue)) {
                try {
                    $next($message);
                } catch (Throwable $exception) {
                    $this->isHandling = false;

                    throw $exception;
                }
            }

            $this->isHandling = false;
        }
    }
}
