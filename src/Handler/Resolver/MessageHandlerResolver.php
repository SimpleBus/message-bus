<?php

namespace SimpleBus\Message\Handler\Resolver;

use SimpleBus\Message\Message;
use SimpleBus\Message\Handler\MessageHandler;

interface MessageHandlerResolver
{
    /**
     * Resolve the MessageHandler for the given Message.
     *
     * @param Message $message
     * @return MessageHandler
     */
    public function resolve(Message $message);
}
