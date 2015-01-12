<?php

namespace SimpleBus\Message\Handler\Map;

use SimpleBus\Message\Handler\Map\Exception\NoHandlerForMessageName;
use SimpleBus\Message\Handler\MessageHandler;

interface MessageHandlerMap
{
    /**
     * @param string $messageName
     * @throws NoHandlerForMessageName
     * @return MessageHandler
     */
    public function handlerByMessageName($messageName);
}
