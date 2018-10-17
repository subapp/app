<?php

namespace Subapp\WebApp\Action;

/**
 * Class CallbackAction
 * @package Subapp\WebApp\Action
 */
class CallbackAction extends AbstractAction
{
    
    /**
     * @var Callback
     */
    private $callback;
    
    /**
     * CallbackFunctionAction constructor.
     * @param Callback $callback
     */
    public function __construct(Callback $callback)
    {
        $this->callback = $callback;
    }
    
    /**
     * @return Callback
     */
    public function getCallback()
    {
        return $this->callback;
    }
    
    /**
     * @param Callback $callback
     */
    public function setCallback(Callback $callback)
    {
        $this->callback = $callback;
    }
    
}