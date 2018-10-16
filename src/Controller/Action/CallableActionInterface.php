<?php

namespace Subapp\WebApp\Controller\Action;

/**
 * Interface CallableActionInterface
 * @package Subapp\WebApp\Controller\Action
 */
interface CallableActionInterface
{
    
    /**
     * @return CallbackFunction
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