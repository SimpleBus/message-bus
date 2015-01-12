<?php

namespace SimpleBus\Message\Tests\Subscriber\Collection;

use SimpleBus\Message\Subscriber\Collection\LazyLoadingMessageSubscriberCollection;
use stdClass;

class LazyLoadingMessageSubscriberCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_loads_known_message_subscribers()
    {
        $messageSubscriberService1 = $this->dummyMessageSubscriber();
        $messageSubscriberService2 = $this->dummyMessageSubscriber();

        $collection = new LazyLoadingMessageSubscriberCollection(
            [
                'Known\Message' => [
                    'known_message_subscriber_service_1_id',
                    'known_message_subscriber_service_2_id'
                ]
            ],
            $this->stubServiceLocator(
                [
                    'known_message_subscriber_service_1_id' => $messageSubscriberService1,
                    'known_message_subscriber_service_2_id' => $messageSubscriberService2
                ]
            )
        );

        $this->assertSame(
            [$messageSubscriberService1, $messageSubscriberService2],
            $collection->subscribersByMessageName('Known\Message')
        );
    }

    /**
     * @test
     */
    public function it_returns_an_empty_list_when_there_is_no_message_subscriber_for_the_given_name()
    {
        $collection = new LazyLoadingMessageSubscriberCollection(
            [],
            $this->stubServiceLocator([])
        );

        $this->assertSame([], $collection->subscribersByMessageName('Unknown\Message'));
    }

    /**
     * @test
     */
    public function it_fails_when_a_subscriber_returned_by_the_service_locator_is_not_an_object_of_the_right_class()
    {
        $collection = new LazyLoadingMessageSubscriberCollection(
            [
                'Message\Name' => ['not_a_message_subscriber_service_of_the_right_class_id']
            ],
            $this->stubServiceLocator(
                [
                    'not_a_message_subscriber_service_of_the_right_class_id' => new stdClass()
                ]
            )
        );

        $this->setExpectedException('\LogicException');
        $collection->subscribersByMessageName('Message\Name');
    }

    /**
     * @test
     */
    public function it_fails_when_a_subscriber_returned_by_the_service_locator_is_not_an_object()
    {
        $collection = new LazyLoadingMessageSubscriberCollection(
            [
                'Message\Name' => ['not_an_object_service_id']
            ],
            $this->stubServiceLocator(
                [
                    'not_an_object_service_id' => 'not an object'
                ]
            )
        );

        $this->setExpectedException('\LogicException');
        $collection->subscribersByMessageName('Message\Name');
    }

    /**
     * @test
     */
    public function it_fails_when_the_service_locator_fails_to_load_a_message_subscriber_service()
    {
        $collection = new LazyLoadingMessageSubscriberCollection(
            [
                'Message\Name' => 'invalid_message_subscriber_service_id'
            ],
            function () {
                throw new \Exception('Always failing service locator');
            }
        );

        $this->setExpectedException('\Exception');
        $collection->subscribersByMessageName('Message\Name');
    }

    private function dummyMessageSubscriber()
    {
        return $this->getMock('SimpleBus\Message\Subscriber\MessageSubscriber');
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
