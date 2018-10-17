<?php

namespace Subapp\WebApp\Action;

/**
 * Class AbstractAction
 * @package Subapp\WebApp\Action
 */
abstract class AbstractAction implements ActionInterface
{
    
    /**
     * @var array
     */
    private $arguments = [];
    
    /**
     * @return Callback
     */
    abstract public function getCallback();
    
    /**
     * @return mixed
     */
    public function executeCallback()
    {
        return $this->getCallback()(...array_values($this->getArguments()));
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