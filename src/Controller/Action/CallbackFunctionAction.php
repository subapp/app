<?php

namespace Subapp\WebApp\Controller\Action;

/**
 * Class CallbackFunctionAction
 * @package Subapp\WebApp\Controller\Action
 */
class CallbackFunctionAction extends AbstractAction
{
    
    /**
     * @var CallbackFunction
     */
    private $callback;
    
    /**
     * CallbackFunctionAction constructor.
     * @param CallbackFunction $callback
     */
    public function __construct(CallbackFunction $callback)
    {
        $this->callback = $callback;
    }
    
    /**
     * @return CallbackFunction
     */
    public function getCallback()
    {
        return $this->callback;
    }
    
    /**
     * @param CallbackFunction $callback
     */
    public function setCallback(CallbackFunction $callback)
    {
        $this->callback = $callback;
    }
    
}