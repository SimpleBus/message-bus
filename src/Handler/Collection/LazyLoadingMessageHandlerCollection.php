<?php

namespace SimpleBus\Message\Handler\Collection;

use Exception;
use SimpleBus\Message\Handler\Collection\Exception\CouldNotLoadHandlerService;
use SimpleBus\Message\Handler\Collection\Exception\InvalidMessageHandler;
use SimpleBus\Message\Handler\Collection\Exception\NoHandlerForMessageName;
use SimpleBus\Message\Handler\MessageHandler;

class LazyLoadingMessageHandlerCollection implements MessageHandlerCollection
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
     * @throws CouldNotLoadHandlerService
     * @throws InvalidMessageHandler
     */
    public function getByMessageName($messageName)
    {
        if (!isset($this->messageHandlerServiceIds[$messageName])) {
            throw new NoHandlerForMessageName($messageName);
        }

        return $this->loadHandlerService($this->messageHandlerServiceIds[$messageName]);
    }

    private function loadHandlerService($handlerServiceId)
    {
        try {
            $messageHandler = call_user_func($this->serviceLocator, $handlerServiceId);
        } catch (Exception $previous) {
            throw new CouldNotLoadHandlerService($handlerServiceId, $previous);
        }

        if (!($messageHandler instanceof MessageHandler)) {
            throw new InvalidMessageHandler($messageHandler);
        }

        return $messageHandler;
    }
}
