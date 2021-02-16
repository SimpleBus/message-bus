<?php

namespace SimpleBus\Message\Tests\Message;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\Tests\Bus\Fixtures\StubMessageBusMiddleware;
use stdClass;
use Throwable;
use TypeError;

/**
 * @internal
 * @coversNothing
 */
class FinishesMessageBeforeHandlingNextTest extends TestCase
{
    /**
     * @test
     */
    public function itFinishesHandlingAMessageBeforeHandlingTheNext(): void
    {
        $originalMessage = $this->dummyMessage();
        $newMessage = $this->dummyMessage();
        $whatHappened = [];

        $messageBus = new MessageBusSupportingMiddleware();
        $messageBus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());
        $messageBus->appendMiddleware(
            // the next message bus that will be called
            new StubMessageBusMiddleware(
                function ($actualMessage) use ($originalMessage, $newMessage, $messageBus, &$whatHappened) {
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
                'finished handling new message',
            ],
            $whatHappened
        );
    }

    /**
     * @test
     */
    public function itRethrowsACaughtExceptionsAndIsAbleToHandleNewMessagesAfterwards(): void
    {
        $message1 = $this->dummyMessage();
        $message2 = $this->dummyMessage();
        $handledMessages = [];
        $exceptionForMessage1 = new TypeError();

        $messageBus = new MessageBusSupportingMiddleware();
        $messageBus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());
        $messageBus->appendMiddleware(
            // the next message bus that will be called
            new StubMessageBusMiddleware(
                function ($actualMessage) use ($message1, $exceptionForMessage1, &$handledMessages) {
                    $handledMessages[] = $actualMessage;

                    if ($message1 === $actualMessage) {
                        // the first message triggers an exception
                        throw $exceptionForMessage1;
                    }
                }
            )
        );

        try {
            $messageBus->handle($message1);
            $this->fail('An exception should have been thrown');
        } catch (Throwable $actualException) {
            $this->assertSame($exceptionForMessage1, $actualException);
        }

        // the message bus should still be able to handle another message
        $messageBus->handle($message2);

        $this->assertSame([$message1, $message2], $handledMessages);
    }

    private function dummyMessage(): stdClass
    {
        return new stdClass();
    }
}
