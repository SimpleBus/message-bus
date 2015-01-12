<?php

namespace SimpleBus\Message\Subscriber;

use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;
use SimpleBus\Message\Message;
use SimpleBus\Message\Subscriber\Resolver\MessageSubscribersResolver;

class NotifiesMessageSubscribersMiddleware implements MessageBusMiddleware
{
    /**
     * @var MessageSubscribersResolver
     */
    private $messageSubscribersResolver;

    public function __construct(MessageSubscribersResolver $messageSubscribersResolver)
    {
        $this->messageSubscribersResolver = $messageSubscribersResolver;
    }

    public function handle(Message $message, callable $next)
    {
        $messageSubscribers = $this->messageSubscribersResolver->resolve($message);

        foreach ($messageSubscribers as $messageSubscriber) {
            $messageSubscriber->notify($message);
        }

        $next($message);
    }
}
