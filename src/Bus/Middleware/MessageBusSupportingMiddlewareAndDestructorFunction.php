<?php

namespace SimpleBus\Message\Bus\Middleware;

use SimpleBus\Message\Bus\MessageBus;

/**
 * Implementation of a MessageBus with support for a callable which is invoked at shutdown / destruction of the object.
 *
 * @package SimpleBus\Message\Bus\Middleware
 */
class MessageBusSupportingMiddlewareAndDestructorFunction implements MessageBus
{

    /**
     * The MessageBus actually processing the Messages.
     *
     * @var MessageBusSupportingMiddleware
     */
    private $messageBusSupportingMiddleware;

    /**
     * @var int
     */
    private $numberOfHandledMessages = 0;

    /**
     * @var callable
     */
    private $shutdownFunction;

    /**
     * @var array
     */
    private $shutdownFunctionParameters;

    public function __construct(array $middlewares = [], callable $shutdownFunction, ...$shutdownFunctionParameters)
    {
        $this->messageBusSupportingMiddleware = new MessageBusSupportingMiddleware($middlewares);
        $this->shutdownFunction = $shutdownFunction;
        $this->shutdownFunctionParameters = $shutdownFunctionParameters;
    }

    public function handle($message)
    {
        $this->numberOfHandledMessages++;
        $this->messageBusSupportingMiddleware->handle($message);
    }

    public function __destruct()
    {
        if ($this->numberOfHandledMessages > 0) {
            call_user_func_array($this->shutdownFunction, $this->shutdownFunctionParameters);
        }
    }
}
