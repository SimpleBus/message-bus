<?php

namespace SimpleBus\Message\Tests\CallableResolver;

use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Tests\CallableResolver\Fixtures\LegacyHandler;
use SimpleBus\Message\Tests\CallableResolver\Fixtures\LegacySubscriber;
use SimpleBus\Message\Tests\CallableResolver\Fixtures\SubscriberWithCustomNotify;

/**
 * @internal
 * @coversNothing
 */
class ServiceLocatorAwareCallableResolverTest extends TestCase
{
    /**
     * @var ServiceLocatorAwareCallableResolver
     */
    private $resolver;

    private $services = [];

    private $serviceLocator;

    protected function setUp(): void
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
    public function itReturnsAHandlerIfItIsACallable()
    {
        $callable = function () {
        };

        $this->assertSame($callable, $this->resolver->resolve($callable));
    }

    /**
     * @test
     */
    public function itReturnsACallableService()
    {
        $callable = function () {
        };
        $this->services['callable_service_id'] = $callable;

        $this->assertSame($callable, $this->resolver->resolve('callable_service_id'));
    }

    /**
     * @test
     */
    public function itReturnsACallableServiceAndMethod()
    {
        $callable = function () {
        };
        $this->services['callable_service_id'] = $callable;

        $this->assertSame([$callable, '__invoke'], $this->resolver->resolve(['callable_service_id', '__invoke']));
    }

    /**
     * @test
     */
    public function itFailsIfObjectAndMethodArrayIsNotACallable()
    {
        $this->expectException('SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable');
        $this->resolver->resolve([new \stdClass(), 'nonExistingMethod']);
    }

    /**
     * @test
     */
    public function itFailsIfTheLoadedServiceIsNotCallable()
    {
        $this->services['not_a_callable'] = new \stdClass();

        $this->expectException('SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable');
        $this->expectExceptionMessage('stdClass could not be resolved to a valid callable');
        $this->resolver->resolve('not_a_callable');
    }

    /**
     * @test
     */
    public function itFailsIfTheLoadedServiceIsNotCallableDoesNotListChildService()
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
    public function itFailsIfTheLoadedServiceAndMethodArrayIsNotCallable()
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
    public function itFailsIfTheLoadedServiceAndMethodArrayIsNotCallableDoesNotListChildService()
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
    public function itUsesTheHandleMethodIfItExists()
    {
        $legacyHandler = new LegacyHandler();

        $this->assertSame([$legacyHandler, 'handle'], $this->resolver->resolve($legacyHandler));
    }

    /**
     * @test
     */
    public function itUsesTheNotifyMethodIfItExists()
    {
        $legacySubscriber = new LegacySubscriber();

        $this->assertSame([$legacySubscriber, 'notify'], $this->resolver->resolve($legacySubscriber));
    }

    /**
     * @test
     */
    public function itSupportsClassBasedServices()
    {
        $subscriber = new SubscriberWithCustomNotify();

        $this->services['SimpleBus\Message\Tests\CallableResolver\Fixtures\SubscriberWithCustomNotify'] = $subscriber;

        $callable = [
            'serviceId' => 'SimpleBus\Message\Tests\CallableResolver\Fixtures\SubscriberWithCustomNotify',
            'method' => 'customNotifyMethod',
        ];
        $this->assertSame([$subscriber, 'customNotifyMethod'], $this->resolver->resolve($callable));
    }
}
