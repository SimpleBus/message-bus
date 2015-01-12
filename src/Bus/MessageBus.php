<?php

namespace SimpleBus\Message\Bus;

use SimpleBus\Message\Message;

interface MessageBus
{
    /**
     * @param Message $message
     * @return void
     */
    public function handle(Message $message);
}
