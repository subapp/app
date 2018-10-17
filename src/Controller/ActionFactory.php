<?php

namespace Subapp\WebApp\Controller;

use Subapp\WebApp\Action\ActionInterface;
use Subapp\WebApp\Action\Callback;
use Subapp\WebApp\Action\CallbackAction;
use Subapp\WebApp\Action\ClassMethodAction;

/**
 * Class ActionFactory
 * @package Subapp\WebApp\Action
 */
class ActionFactory
{
    
    /**
     * @param $callback
     * @return ActionInterface
     * @throws \InvalidArgumentException
     */
    public function getAction($callback)
    {
        switch (true) {
            case is_array($callback):
                return new ClassMethodAction(...$callback);
            case is_callable($callback):
                return new CallbackAction(new Callback($callback));
            default:
                throw new \InvalidArgumentException(sprintf('Unsupported callback with type (%s)',
                    is_object($callback) ? get_class($callback) : gettype($callback)));
        }
    }

}