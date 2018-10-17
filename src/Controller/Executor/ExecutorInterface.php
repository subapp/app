<?php

namespace Subapp\WebApp\Controller\Executor;

use Subapp\WebApp\Action\ActionInterface;
use Subapp\WebApp\Controller\ResultInterface;

/**
 * Interface ExecutorInterface
 * @package Subapp\WebApp\Controller\Executor
 */
interface ExecutorInterface
{

    /**
     * @return ResultInterface
     */
    public function execute();

    /**
     * @return ActionInterface
     */
    public function getAction();

    /**
     * @param ActionInterface $action
     */
    public function setAction(ActionInterface $action);

}