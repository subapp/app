<?php

namespace Subapp\WebApp\Controller\Action;

/**
 * Abstract Class AbstractAction
 * @package Subapp\WebApp\Controller
 */
abstract class AbstractAction implements CallableActionInterface
{
    
    /**
     * @var array
     */
    private $arguments = [];
    
    /**
     * @return CallbackFunction
     */
    abstract public function getCallback();
    
    /**
     * @return mixed
     */
    public function executeCallback()
    {
        $callback = $this->getCallback();
        
        return $callback->call($this->arguments);
    }
    
    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }
    
    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }
    
}