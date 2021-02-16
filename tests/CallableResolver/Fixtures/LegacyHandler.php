<?php

namespace SimpleBus\Message\Tests\CallableResolver\Fixtures;

class LegacyHandler
{
    public function handle(object $message): void
    {
    }
}
