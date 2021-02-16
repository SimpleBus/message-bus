<?php

namespace SimpleBus\Message\Bus;

interface MessageBus
{
    public function handle(object $message): void;
}
