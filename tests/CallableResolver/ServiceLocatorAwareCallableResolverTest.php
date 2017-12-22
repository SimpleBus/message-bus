<?php

namespace SimpleBus\Message\Tests\CallableResolver;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Tests\CallableResolver\Fixtures\LegacyHandler;
use SimpleBus\Message\Tests\CallableResolver\Fixtures\LegacySubscriber;
use SimpleBus\Message\Tests\CallableResolver\Fixtures\SubscriberWithCustomNotify;

class ServiceLocatorAwareCallableResolverTest extends TestCase
{
    /**
     * @var ServiceLocatorAwareCallableResolver
     */
    private $resolver;

    private $services = [];

    private $serviceLocator;

    protected function setUp()
    {
        $this->services = [];
        $this->serviceLocator = function ($serviceId) {
            return $this->services[$serviceId];
        };
        $this->resolver = new ServiceLocatorAwareCallableResolver($this->serviceLocator);
    }

    /**
     * @test
     */
    public function it_returns_a_handler_if_it_is_a_callable()
    {
        $callable = function () {
        };

        $this->assertSame($callable, $this->resolver->resolve($callable));
    }

    /**
     * @test
     */
    public function it_returns_a_callable_service()
    {
        $callable = function () {
        };
        $this->services['callable_service_id'] = $callable;

        $this->assertSame($callable, $this->resolver->resolve('callable_service_id'));
    }

    /**
     * @test
     */
    public function it_returns_a_callable_service_and_method()
    {
        $callable = function () {
        };
        $this->services['callable_service_id'] = $callable;

        $this->assertSame([$callable, '__invoke'], $this->resolver->resolve(['callable_service_id', '__invoke']));
    }

    /**
     * @test
     */
    public function it_fails_if_object_and_method_array_is_not_a_callable()
    {
        $this->expectException('SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable');
        $this->resolver->resolve([new \stdClass(), 'nonExistingMethod']);
    }

    /**
     * @test
     */
    public function it_fails_if_the_loaded_service_is_not_callable()
    {
        $this->services['not_a_callable'] = new \stdClass();

        $this->expectException('SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable');
        $this->expectExceptionMessage('stdClass could not be resolved to a valid callable');
        $this->resolver->resolve('not_a_callable');
    }

    /**
     * @test
     */
    public function it_fails_if_the_loaded_service_is_not_callable_does_not_list_child_service()
    {
        $handler = new \stdClass();
        $handler->childService = new \stdClass();

        $this->services['not_a_callable'] = $handler;

        $this->expectException('SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable');
        $this->expectExceptionMessage('stdClass could not be resolved to a valid callable');
        $this->resolver->resolve('not_a_callable');
    }

    /**
     * @test
     */
    public function it_fails_if_the_loaded_service_and_method_array_is_not_callable()
    {
        $this->services['callable_service_id'] = new \stdClass();

        $this->expectException('SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable');
        $this->expectExceptionMessage(
            'Array([0] => stdClass[1] => nonExistingMethod) could not be resolved to a valid callable'
        );
        $this->resolver->resolve(['callable_service_id', 'nonExistingMethod']);
    }

    /**
     * @test
     */
    public function it_fails_if_the_loaded_service_and_method_array_is_not_callable_does_not_list_child_service()
    {
        $handler = new \stdClass();
        $handler->childService = new \stdClass();

        $this->services['callable_service_id'] = $handler;

        $this->expectException('SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable');
        $this->expectExceptionMessage(
            'Array([0] => stdClass[1] => nonExistingMethod) could not be resolved to a valid callable'
        );
        $this->resolver->resolve(['callable_service_id', 'nonExistingMethod']);
    }

    /**
     * @test
     */
    public function it_uses_the_handle_method_if_it_exists()
    {
        $legacyHandler = new LegacyHandler();

        $this->assertSame([$legacyHandler, 'handle'], $this->resolver->resolve($legacyHandler));
    }

    /**
     * @test
     */
    public function it_uses_the_notify_method_if_it_exists()
    {
        $legacySubscriber = new LegacySubscriber();

        $this->assertSame([$legacySubscriber, 'notify'], $this->resolver->resolve($legacySubscriber));
    }

    /**
     * @test
     */
    public function it_supports_class_based_services()
    {
        $subscriber = new SubscriberWithCustomNotify();

        $this->services['SimpleBus\Message\Tests\CallableResolver\Fixtures\SubscriberWithCustomNotify'] = $subscriber;

        $callable = [
            'serviceId' => 'SimpleBus\Message\Tests\CallableResolver\Fixtures\SubscriberWithCustomNotify',
            'method'    => 'customNotifyMethod',
        ];
        $this->assertSame([$subscriber, 'customNotifyMethod'], $this->resolver->resolve($callable));
    }
}
