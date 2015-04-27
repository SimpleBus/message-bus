<?php

namespace SimpleBus\Message\Tests\CallableResolver;

use SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;

class ServiceLocatorAwareCallableResolverTest extends \PHPUnit_Framework_TestCase
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
        $this->setExpectedException(CouldNotResolveCallable::class);
        $this->resolver->resolve([new \stdClass(), 'nonExistingMethod']);
    }

    /**
     * @test
     */
    public function it_fails_if_the_loaded_service_is_not_callable()
    {
        $this->services['not_a_callable'] = new \stdClass();

        $this->setExpectedException(CouldNotResolveCallable::class);
        $this->resolver->resolve('not_a_callable');
    }

    /**
     * @test
     */
    public function it_fails_if_the_loaded_service_and_method_array_is_not_callable()
    {
        $this->services['callable_service_id'] = new \stdClass();

        $this->setExpectedException(CouldNotResolveCallable::class);
        $this->resolver->resolve(['callable_service_id', 'nonExistingMethod']);
    }
}
