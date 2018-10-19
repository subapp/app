<?php

namespace Subapp\WebApp\Controller;

use Subapp\WebApp\Action\ActionInterface;
use Subapp\WebApp\Action\CallbackAction;
use Subapp\WebApp\Controller\Executor\ControllerActionExecutor;
use Subapp\WebApp\Controller\Executor\ExecutorInterface;
use Subapp\WebApp\Controller\Executor\FunctionExecutor;

/**
 * Class ExecutorFactory
 * @package Subapp\WebApp\Controller
 */
class ExecutorFactory
{

    /**
     * @param ActionInterface $action
     * @return ExecutorInterface
     * @throws \InvalidArgumentException
     */
    public function getExecutor(ActionInterface $action)
    {
        switch (true) {
            case ($action instanceof ControllerClassMethod):
                return new ControllerActionExecutor($action);
                break;
            case ($action instanceof CallbackAction):
                return new FunctionExecutor($action);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unsupported action (%s) object', get_class($action)));
                break;
        }
    }

}