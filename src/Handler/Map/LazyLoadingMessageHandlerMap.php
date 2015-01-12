<?php

namespace SimpleBus\Message\Handler\Map;

use Assert\Assertion;
use SimpleBus\Message\Handler\Map\Exception\NoHandlerForMessageName;

class LazyLoadingMessageHandlerMap implements MessageHandlerMap
{
    /**
     * @var array
     */
    private $messageHandlerServiceIds;

    /**
     * @var callable
     */
    private $serviceLocator;

    public function __construct(array $messageHandlerServiceIds, callable $serviceLocator)
    {
        $this->messageHandlerServiceIds = $messageHandlerServiceIds;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * {@inheritdoc}
     */
    public function handlerByMessageName($messageName)
    {
        if (!isset($this->messageHandlerServiceIds[$messageName])) {
            throw new NoHandlerForMessageName($messageName);
        }

        return $this->loadHandlerService($this->messageHandlerServiceIds[$messageName]);
    }

    private function loadHandlerService($handlerServiceId)
    {
        $messageHandler = call_user_func($this->serviceLocator, $handlerServiceId);

        Assertion::isInstanceOf($messageHandler, 'SimpleBus\Message\Handler\MessageHandler');

        return $messageHandler;
    }
}
