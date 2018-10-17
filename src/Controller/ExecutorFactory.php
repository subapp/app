<?php

namespace Subapp\WebApp\Controller;

use Subapp\WebApp\Action\ActionInterface;
use Subapp\WebApp\Controller\Executor\ControllerActionExecutor;

/**
 * Class ExecutorFactory
 * @package Subapp\WebApp\Controller
 */
class ExecutorFactory
{

    /**
     * @param ActionInterface $action
     * @return ControllerActionExecutor
     * @throws \InvalidArgumentException
     */
    public function getExecutor(ActionInterface $action)
    {
        switch (true) {
            case ($action instanceof ControllerClassMethod):
                return new ControllerActionExecutor($action);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unsupported action (%s) object', get_class($action)));
                break;
        }
    }

}