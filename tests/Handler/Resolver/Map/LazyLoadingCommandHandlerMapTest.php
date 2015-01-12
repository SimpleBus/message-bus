<?php

namespace SimpleBus\Message\Tests\Handler\Map;

use SimpleBus\Message\Handler\Map\LazyLoadingMessageHandlerMap;
use stdClass;

class LazyLoadingMessageHandlerMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_loads_a_known_message_handler()
    {
        $messageHandlerService = $this->dummyMessageHandler();

        $map = new LazyLoadingMessageHandlerMap(
            [
                'Known\Message' => 'known_message_handler_service_id'
            ],
            $this->stubServiceLocator(
                [
                    'known_message_handler_service_id' => $messageHandlerService
                ]
            )
        );

        $this->assertSame($messageHandlerService, $map->handlerByMessageName('Known\Message'));
    }

    /**
     * @test
     */
    public function it_fails_when_there_is_no_message_handler_for_the_given_name()
    {
        $map = new LazyLoadingMessageHandlerMap(
            [],
            $this->stubServiceLocator([])
        );

        $this->setExpectedException(
            'SimpleBus\Message\Handler\Map\Exception\NoHandlerForMessageName'
        );
        $map->handlerByMessageName('Unknown\Message');
    }

    /**
     * @test
     */
    public function it_fails_when_the_handler_returned_by_the_service_locator_is_not_an_object_of_the_right_class()
    {
        $map = new LazyLoadingMessageHandlerMap(
            [
                'Message\Name' => 'not_a_message_handler_service_of_the_right_class_id'
            ],
            $this->stubServiceLocator(
                [
                    'not_a_message_handler_service_of_the_right_class_id' => new stdClass()
                ]
            )
        );

        $this->setExpectedException('\LogicException');
        $map->handlerByMessageName('Message\Name');
    }

    /**
     * @test
     */
    public function it_fails_when_the_handler_returned_by_the_service_locator_is_not_an_object()
    {
        $map = new LazyLoadingMessageHandlerMap(
            [
                'Message\Name' => 'not_an_object_service_id'
            ],
            $this->stubServiceLocator(
                [
                    'not_an_object_service_id' => 'not an object'
                ]
            )
        );

        $this->setExpectedException('\LogicException');
        $map->handlerByMessageName('Message\Name');
    }

    /**
     * @test
     */
    public function it_fails_when_the_service_locator_fails_to_load_the_message_handler_service()
    {
        $map = new LazyLoadingMessageHandlerMap(
            [
                'Message\Name' => 'invalid_message_handler_service_id'
            ],
            function () {
                throw new \Exception('Always failing service locator');
            }
        );

        $this->setExpectedException('\Exception');
        $map->handlerByMessageName('Message\Name');
    }

    private function dummyMessageHandler()
    {
        return $this->getMock('SimpleBus\Message\Handler\MessageHandler');
    }

    private function stubServiceLocator(array $knownServices)
    {
        return function ($id) use ($knownServices) {
            if (!isset($knownServices[$id])) {
                $this->fail('Unknown service requested');
            }

            return $knownServices[$id];
        };
    }
}
