<?php

namespace SimpleBus\Message\Subscriber;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use SimpleBus\Message\Subscriber\Resolver\MessageSubscribersResolver;

class NotifiesMessageSubscribersMiddleware implements MessageBusMiddleware
{
    private MessageSubscribersResolver $messageSubscribersResolver;

    private LoggerInterface $logger;

    private string $level;

    public function __construct(
        MessageSubscribersResolver $messageSubscribersResolver,
        LoggerInterface $logger = null,
        string $level = null
    ) {
        $this->messageSubscribersResolver = $messageSubscribersResolver;
        $this->logger = $logger ?? new NullLogger();
        $this->level = $level ?? LogLevel::DEBUG;
    }

    public function handle(object $message, callable $next): void
    {
        $messageSubscribers = $this->messageSubscribersResolver->resolve($message);

        foreach ($messageSubscribers as $messageSubscriber) {
            $this->logger->log($this->level, 'Started notifying a subscriber', ['subscriber' => $messageSubscriber]);

            call_user_func($messageSubscriber, $message);

            $this->logger->log($this->level, 'Finished notifying a subscriber', ['subscriber' => $messageSubscriber]);
        }

        $next($message);
    }
}
