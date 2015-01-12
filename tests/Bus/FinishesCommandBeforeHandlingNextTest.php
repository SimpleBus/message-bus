<?php

namespace SimpleBus\Message\Tests\Message;

use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\Message;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Tests\Bus\Fixtures\StubMessageBusMiddleware;

class FinishesMessageBeforeHandlingNextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_finishes_handling_a_message_before_handling_the_next()
    {
        $originalMessage = $this->dummyMessage();
        $newMessage = $this->dummyMessage();
        $whatHappened = [];

        $messageBus = new MessageBusSupportingMiddleware();
        $messageBus->addMiddleware(new FinishesHandlingMessageBeforeHandlingNext());
        $messageBus->addMiddleware(
            // the next message bus that will be called
            new StubMessageBusMiddleware(
                function (Message $actualMessage) use ($originalMessage, $newMessage, $messageBus, &$whatHappened) {
                    $handledMessages[] = $actualMessage;

                    if ($actualMessage === $originalMessage) {
                        $whatHappened[] = 'start handling original message';
                        // while handling the original we trigger a new message
                        $messageBus->handle($newMessage);
                        $whatHappened[] = 'finished handling original message';
                    } elseif ($actualMessage === $newMessage) {
                        $whatHappened[] = 'start handling new message';
                        $whatHappened[] = 'finished handling new message';
                    }
                }
            )
        );

        $messageBus->handle($originalMessage);

        $this->assertSame(
            [
                'start handling original message',
                'finished handling original message',
                'start handling new message',
                'finished handling new message'
            ],
            $whatHappened
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Message
     */
    private function dummyMessage()
    {
        return $this->getMock('SimpleBus\Message\Message');
    }
}
