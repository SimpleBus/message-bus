<?php

namespace SimpleBus\Message\Tests\Bus;

use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddlewareAndDestructorFunction;

class MessageBusSupportingMiddlewareAndDestructorTest extends \PHPUnit\Framework\TestCase
{
    private static $locallyStoredMessage;

    /**
     * @test
     */
    public function it_invokes_the_callback_after_handling_middleware()
    {
        $anonymousCallbackClass = new class{
            /**
             * @var string
             */
            public $locallyStoredMessage;

            public function storeMessageLocally($messageToStoreLocally)
            {
                $this->locallyStoredMessage = $messageToStoreLocally;
            }
        };
        $anonymousCallbackObject = new $anonymousCallbackClass();
        $callback = array($anonymousCallbackObject, 'storeMessageLocally');
        $messageToStoreLocally = 'Hello World';

        $messageBusStack = new MessageBusSupportingMiddlewareAndDestructorFunction([], $callback, $messageToStoreLocally);

        $messageBusStack->handle(new \stdClass());
        unset($messageBusStack); //Trigger object destruction

        $this->assertEquals($anonymousCallbackObject->locallyStoredMessage, $messageToStoreLocally);
    }
    
}
