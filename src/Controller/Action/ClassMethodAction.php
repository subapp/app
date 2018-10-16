<?php

namespace Subapp\WebApp\Controller\Action;

/**
 * Class ClassMethodAction
 * @package Subapp\WebApp\Controller\Action
 */
class ClassMethodAction extends AbstractAction
{
    
    /**
     * @var string
     */
    private $namespace;
    
    /**
     * @var string
     */
    private $controller;
    
    /**
     * @var string
     */
    private $action;
    
    /**
     * FallbackAction constructor.
     * @param string $namespace
     * @param string $controller
     * @param string $action
     */
    public function __construct($namespace, $controller, $action)
    {
        $this->namespace = $namespace;
        $this->controller = $controller;
        $this->action = $action;
    }
    
    /**
     * @return callable|CallbackFunction
     */
    public function getCallback()
    {
        $className = sprintf('%s\%s', $this->getNamespace(), $this->getController());
        
        return new CallbackFunction([$className, $this->getAction()]);
    }
    
    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    
    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
    
    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }
    
    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }
    
    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }
    
}