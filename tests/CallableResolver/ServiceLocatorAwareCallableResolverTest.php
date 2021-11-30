<?php

namespace SimpleBus\Message\Tests\CallableResolver;

use Closure;
use PHPUnit\Framework\TestCase;
use SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Tests\CallableResolver\Fixtures\LegacyHandler;
use SimpleBus\Message\Tests\CallableResolver\Fixtures\LegacySubscriber;
use SimpleBus\Message\Tests\CallableResolver\Fixtures\SubscriberWithCustomNotify;
use stdClass;

class ServiceLocatorAwareCallableResolverTest extends TestCase
{
    private ServiceLocatorAwareCallableResolver $resolver;

    /**
     * @var array<string, callable|object>
     */
    private array $services = [];

    private Closure $serviceLocator;

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
    public function itReturnsAHandlerIfItIsACallable(): void
    {
        $callable = function () {
        };

        $this->assertSame($callable, $this->resolver->resolve($callable));
    }

    /**
     * @test
     */
    public function itReturnsACallableService(): void
    {
        $callable = function () {
        };
        $this->services['callable_service_id'] = $callable;

        $this->assertSame($callable, $this->resolver->resolve('callable_service_id'));
    }

    /**
     * @test
     */
    public function itReturnsACallableServiceAndMethod(): void
    {
        $callable = function () {
        };
        $this->services['callable_service_id'] = $callable;

        $this->assertSame([$callable, '__invoke'], $this->resolver->resolve(['callable_service_id', '__invoke']));
    }

    /**
     * @test
     */
    public function itFailsIfObjectAndMethodArrayIsNotACallable(): void
    {
        $this->expectException(CouldNotResolveCallable::class);
        $this->resolver->resolve([new stdClass(), 'nonExistingMethod']);
    }

    /**
     * @test
     */
    public function itFailsIfTheLoadedServiceIsNotCallable(): void
    {
        $this->services['not_a_callable'] = new stdClass();

        $this->expectException(CouldNotResolveCallable::class);
        $this->expectExceptionMessage('stdClass could not be resolved to a valid callable');
        $this->resolver->resolve('not_a_callable');
    }

    /**
     * @test
     */
    public function itFailsIfTheLoadedServiceIsNotCallableDoesNotListChildService(): void
    {
        $handler = new stdClass();
        $handler->childService = new stdClass();

        $this->services['not_a_callable'] = $handler;

        $this->expectException(CouldNotResolveCallable::class);
        $this->expectExceptionMessage('stdClass could not be resolved to a valid callable');
        $this->resolver->resolve('not_a_callable');
    }

    /**
     * @test
     */
    public function itFailsIfTheLoadedServiceAndMethodArrayIsNotCallable(): void
    {
        $this->services['callable_service_id'] = new stdClass();

        $this->expectException(CouldNotResolveCallable::class);
        $this->expectExceptionMessage(
            'Array([0] => stdClass[1] => nonExistingMethod) could not be resolved to a valid callable'
        );
        $this->resolver->resolve(['callable_service_id', 'nonExistingMethod']);
    }

    /**
     * @test
     */
    public function itFailsIfTheLoadedServiceAndMethodArrayIsNotCallableDoesNotListChildService(): void
    {
        $handler = new stdClass();
        $handler->childService = new stdClass();

        $this->services['callable_service_id'] = $handler;

        $this->expectException(CouldNotResolveCallable::class);
        $this->expectExceptionMessage(
            'Array([0] => stdClass[1] => nonExistingMethod) could not be resolved to a valid callable'
        );
        $this->resolver->resolve(['callable_service_id', 'nonExistingMethod']);
    }

    /**
     * @test
     */
    public function itUsesTheHandleMethodIfItExists(): void
    {
        $legacyHandler = new LegacyHandler();

        $this->assertSame([$legacyHandler, 'handle'], $this->resolver->resolve($legacyHandler));
    }

    /**
     * @test
     */
    public function itUsesTheNotifyMethodIfItExists(): void
    {
        $legacySubscriber = new LegacySubscriber();

        $this->assertSame([$legacySubscriber, 'notify'], $this->resolver->resolve($legacySubscriber));
    }

    /**
     * @test
     */
    public function itSupportsClassBasedServices(): void
    {
        $subscriber = new SubscriberWithCustomNotify();

        $this->services[SubscriberWithCustomNotify::class] = $subscriber;

        $callable = [
            'serviceId' => SubscriberWithCustomNotify::class,
            'method' => 'customNotifyMethod',
        ];
        $this->assertSame([$subscriber, 'customNotifyMethod'], $this->resolver->resolve($callable));
    }
}
