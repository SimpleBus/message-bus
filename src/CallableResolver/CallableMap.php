<?php

namespace SimpleBus\Message\CallableResolver;

use SimpleBus\Message\CallableResolver\Exception\CouldNotResolveCallable;
use SimpleBus\Message\CallableResolver\Exception\UndefinedCallable;

class CallableMap
{
    /**
     * @var array
     */
    private $callablesByName;
    
    /**
     * @var CallableResolver
     */
    private $callableResolver;
    
    public function __construct(
        $callablesByName,
        CallableResolver $callableResolver
    ) {
        if(!is_array($callablesByName) && !is_a($callablesByName, \ArrayAccess::class)) {
            throw new CouldNotResolveCallable("Unexpected callables map - pass array or object implementing ArrayAccess");
        }
        
        $this->callablesByName  = $callablesByName;
        $this->callableResolver = $callableResolver;
    }
    
    /**
     * @param string $name
     *
     * @return callable
     */
    public function get($name)
    {
        if(!isset($this->callablesByName[ $name ])) {
            throw new UndefinedCallable(
                sprintf(
                    'Could not find a callable for name "%s"',
                    $name
                )
            );
        }
        
        $callable = $this->callablesByName[ $name ];
        
        return $this->callableResolver->resolve($callable);
    }
}
