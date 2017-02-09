<?php
namespace SimpleBus\Message\Tests\Handler\CallableMap;

use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Tests\Handler\CallableMap\Fixtures\DummyCommand;
use SimpleBus\Message\Tests\Handler\CallableMap\Fixtures\DummyCommandHandler;

class CallableMapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function it_accepts_ArrayAccess_interface_as_a_map()
    {
        $commandHandlerMap = new CallableMap(
            $this->sampleArrayAccess(),
            new ServiceLocatorAwareCallableResolver($this->sampleServiceLocator())
        );
        
        $command_fqcn          = DummyCommand::class;
        $expected_handler_fqcn = DummyCommandHandler::class;
        
        $this->assertEquals($expected_handler_fqcn, $commandHandlerMap->get($command_fqcn)[0]);
    }
    
    /**
     * @test
     */
    function it_accepts_array_as_a_map()
    {
        $command_fqcn = DummyCommand::class;
        $handler_fqcn = DummyCommandHandler::class;
        
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
        $this->setExpectedException(CouldNotResolveCallable::class);
        
        new CallableMap(
            "wrong type",
            new ServiceLocatorAwareCallableResolver($this->sampleServiceLocator())
        );
        
    }
    
    private function sampleArrayAccess()
    {
        return (new class() implements \ArrayAccess
        {
            public function offsetExists($index)
            {
                return class_exists($this->mapCommandToHandler($index), true);
            }
            
            public function offsetGet($index)
            {
                return $this->mapCommandToHandler($index);
            }
            
            public function offsetSet($offset, $value)
            {
                
            }
            
            public function offsetUnset($offset)
            {
                
            }
            
            private function mapCommandToHandler($command_FQCN)
            {
                // this is the strategy to map command to handler
                return $command_FQCN . "Handler";
            }
        });
    }
    
    
    private function sampleServiceLocator()
    {
        return function($service_id) {
            return [$service_id, 'handle'];
        };
    }
}