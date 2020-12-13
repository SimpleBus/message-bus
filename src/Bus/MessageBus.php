<?php

namespace SimpleBus\Message\Bus;

interface MessageBus
{
    /**
     * @param object $message
     */
    public function handle($message);
}
