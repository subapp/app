<?php

namespace Subapp\WebApp\Controller\Executor;

use Subapp\ServiceLocator\ContainerInterface;
use Subapp\WebApp\Action\ActionInterface;

/**
 * Class AbstractExecutor
 * @package Subapp\WebApp\Controller
 */
abstract class AbstractExecutor implements ExecutorInterface
{

    /**
     * @var ActionInterface
     */
    protected $action;

    /**
     * AbstractExecutor constructor.
     * @param ActionInterface $action
     */
    public function __construct(ActionInterface $action)
    {
        $this->action = $action;
    }
    
    /**
     * @inheritdoc
     */
    public function __debugInfo()
    {
        return ['class' => static::class, 'action' => $this->action,];
    }
    
    /**
     * @return ActionInterface
     */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
     * @param ActionInterface $action
     */
    public function setAction(ActionInterface $action)
    {
        $this->action = $action;
    }
    
}
