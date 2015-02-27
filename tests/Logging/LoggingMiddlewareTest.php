<?php

namespace SimpleBus\Message\Tests\Logging;

use Psr\Log\LogLevel;
use SimpleBus\Message\Logging\LoggingMiddleware;
use SimpleBus\Message\Message;

class LoggingMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_logs_messages_before_and_after_handling_it()
    {
        $orderOfEvents = [];
        $message = $this->dummyMessage();

        $logLevel = LogLevel::DEBUG;

        $logger = $this->getMock('Psr\Log\LoggerInterface');
        $logger
            ->expects($this->exactly(2))
            ->method('log')
            ->will($this->returnCallback(function ($actualLevel, $logMessage, array $context) use (&$orderOfEvents, $message, $logLevel) {
                $orderOfEvents[] = 'Logged: ' . $logMessage;
                $this->assertSame(['message' => $message], $context);
                $this->assertSame($logLevel, $actualLevel);
            }));

        $middleware = new LoggingMiddleware($logger, $logLevel);

        $next = function (Message $actualMessage) use (&$orderOfEvents, $message) {
            $orderOfEvents[] = 'Called next middleware';
            $this->assertSame($message, $actualMessage);
        };
        $middleware->handle($message, $next);

        $this->assertSame(
            [
                'Logged: Started handling a message',
                'Called next middleware',
                'Logged: Finished handling a message'
            ],
            $orderOfEvents
        );
    }

    private function dummyMessage()
    {
        return $this->getMock('SimpleBus\Message\Message');
    }
}
