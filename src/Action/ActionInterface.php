<?php

namespace Subapp\WebApp\Action;

/**
 * Interface CallableActionInterface
 * @package Subapp\WebApp\Action
 */
interface ActionInterface
{
    
    /**
     * @return Callback
     */
    public function getCallback();
    
    /**
     * @return mixed
     */
    public function executeCallback();
    
    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments);
    
    /**
     * @return array
     */
    public function getArguments();
    
}