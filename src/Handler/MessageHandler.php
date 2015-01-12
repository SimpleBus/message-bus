<?php

namespace SimpleBus\Message\Handler;

use SimpleBus\Message\Message;

interface MessageHandler
{
    /**
     * Handles the given message.
     *
     * @param Message $message
     * @return void
     */
    public function handle(Message $message);
}
