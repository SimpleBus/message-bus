<?php

namespace SimpleBus\Message\Logging;

use Psr\Log\LoggerInterface;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class LoggingMiddleware implements MessageBusMiddleware
{
    private LoggerInterface $logger;

    /**
     * @var mixed
     */
    private $level;

    /**
     * @param mixed $level
     */
    public function __construct(LoggerInterface $logger, $level)
    {
        $this->logger = $logger;
        $this->level = $level;
    }

    public function handle(object $message, callable $next): void
    {
        $this->logger->log($this->level, 'Started handling a message', ['message' => $message]);

        $next($message);

        $this->logger->log($this->level, 'Finished handling a message', ['message' => $message]);
    }
}
