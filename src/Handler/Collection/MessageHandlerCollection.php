<?php

namespace SimpleBus\Message\Handler\Collection;

use SimpleBus\Message\Handler\Collection\Exception\NoHandlerForMessageName;
use SimpleBus\Message\Handler\MessageHandler;

interface MessageHandlerCollection
{
    /**
     * @param string $messageName
     * @throws NoHandlerForMessageName
     * @return MessageHandler
     */
    public function getByMessageName($messageName);
}
