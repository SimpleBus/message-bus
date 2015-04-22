<?php

namespace SimpleBus\Message\Handler;

interface MessageHandler
{
    /**
     * Handles the given message.
     *
     * @param object $message
     * @return void
     */
    public function handle($message);
}
