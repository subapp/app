<?php

namespace Subapp\WebApp\Action;

/**
 * Class Action
 * @package Subapp\WebApp\Action
 */
class Callback
{
    
    /**
     * @var callable
     */
    protected $callback;
    
    /**
     * Callback constructor.
     * @param $callback
     * @throws \InvalidArgumentException
     */
    public function __construct($callback)
    {
        if (!is_callable($callback, true)) {
            throw new \InvalidArgumentException(
                sprintf('Argument for "%s" constructor should be callable', static::class));
        }
        
        $this->callback = $callback;
    }
    
    /**
     * @param array ...$arguments
     *
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        return $this->call(...$arguments);
    }
    
    /**
     * @param array ...$parameters
     *
     * @return mixed
     */
    public function call(...$parameters)
    {
        return call_user_func($this->getCallback(), ...$parameters);
    }
    
    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }
    
}