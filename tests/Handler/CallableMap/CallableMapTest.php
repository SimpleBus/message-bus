<?php
namespace SimpleBus\Message\Tests\Handler\CallableMap;

use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Tests\Handler\CallableMap\Fixtures\CommandToHandlerMapper;

class CallableMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function it_accepts_ArrayAccess_interface_as_a_map()
    {
        $commandHandlerMap = new CallableMap(
            new CommandToHandlerMapper(),
            new ServiceLocatorAwareCallableResolver($this->sampleServiceLocator())
        );
        
        $command_fqcn          = 'SimpleBus\Message\Tests\Handler\CallableMap\Fixtures\DummyCommand';
        $expected_handler_fqcn = 'SimpleBus\Message\Tests\Handler\CallableMap\Fixtures\DummyCommandHandler';
        
        $this->assertEquals($expected_handler_fqcn, $commandHandlerMap->get($command_fqcn)[0]);
    }
    
    /**
     * @test
     */
    function it_accepts_array_as_a_map()
    {
        $command_fqcn = 'SimpleBus\Message\Tests\Handler\CallableMap\Fixtures\DummyCommand';
        $handler_fqcn = 'SimpleBus\Message\Tests\Handler\CallableMap\Fixtures\DummyCommandHandler';
        
        $commandHandlerMap = new CallableMap(
            [
                $command_fqcn => $handler_fqcn,
            ],
            new ServiceLocatorAwareCallableResolver($this->sampleServiceLocator())
        );
        
        $this->assertEquals($handler_fqcn, $commandHandlerMap->get($command_fqcn)[0]);
    }
    
    /**
     * @test
     */
    function it_wont_accept_wrong_types()
    {
        $this->setExpectedException('SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable');
        
        new CallableMap(
            "wrong type",
            new ServiceLocatorAwareCallableResolver($this->sampleServiceLocator())
        );
        
    }
    
    
    private function sampleServiceLocator()
    {
        return function($service_id) {
            return [$service_id, 'handle'];
        };
    }
}