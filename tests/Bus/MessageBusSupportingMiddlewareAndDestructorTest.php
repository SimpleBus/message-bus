<?php

namespace SimpleBus\Message\Tests\Bus;

use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddlewareAndDestructorFunction;
use SimpleBus\Message\Tests\Bus\Fixtures\CallbackClass;

class MessageBusSupportingMiddlewareAndDestructorTest extends \PHPUnit\Framework\TestCase
{
    private static $locallyStoredMessage;

    /**
     * @test
     */
    public function it_invokes_the_callback_after_handling_middleware()
    {
        $anonymousCallbackObject = new CallbackClass();
        $callback = array($anonymousCallbackObject, 'storeMessageLocally');
        $messageToStoreLocally = 'Hello World';

        $messageBusStack = new MessageBusSupportingMiddlewareAndDestructorFunction([], $callback, $messageToStoreLocally);
        unset($messageBusStack); //Trigger object destruction
        $this->assertEquals($anonymousCallbackObject->locallyStoredMessage, null); //At this point no Message has been handled by the MessageBus yet: callback should NOT be invoked

        $messageBusStack = new MessageBusSupportingMiddlewareAndDestructorFunction([], $callback, $messageToStoreLocally);
        $messageBusStack->handle(new \stdClass());
        unset($messageBusStack); //Trigger object destruction
        $this->assertEquals($anonymousCallbackObject->locallyStoredMessage, $messageToStoreLocally); //At this point a Message has been handled by the MessageBus: callback should be invoked
    }

}
