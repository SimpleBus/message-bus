<?php
namespace SimpleBus\Message\Tests\Handler\CallableMap\Fixtures;

class CommandToHandlerMapper implements \ArrayAccess
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
    
}