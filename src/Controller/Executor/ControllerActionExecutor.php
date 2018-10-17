<?php

namespace Subapp\WebApp\Controller\Executor;

use Subapp\WebApp\Controller\ControllerClassMethod;
use Subapp\WebApp\Controller\ControllerInterface;
use Subapp\WebApp\Controller\Result;
use Subapp\WebApp\Controller\ResultInterface;

/**
 * Class ControllerActionExecutor
 * @package Subapp\WebApp\Controller\Executor
 */
class ControllerActionExecutor extends AbstractExecutor
{

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        $result = new Result();

        /** @var ControllerClassMethod $action */
        $action = $this->getAction();

        /** @var ControllerInterface $controller */
        $controller = $action->getObject();

        // @todo method existing check
        $action->getInstantiator()->getMethodReflection($controller, $action->getMethod());

        $controller->beforeExecute();
        $result->setContent($action->executeCallback());
        $controller->afterExecute();

        $template = sprintf('%s/%s', $action->getShortClassName(), $action->getShortMethodName());

        $result->setLayout($controller->getLayout());
        $result->setPseudoPath($controller->getPseudoPath());
        $result->setTemplate($template);

        return $result;
    }

}