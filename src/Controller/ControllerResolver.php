<?php

namespace Colibri\WebApp\Controller;

use Colibri\ServiceLocator\ContainerInterface;
use Colibri\WebApp\Controller;
use Colibri\WebApp\Exception\RuntimeWebAppException;

/**
 * Class ControllerResolver
 * @package Colibri\WebApp\Controller
 */
class ControllerResolver
{
    
    /**
     * @var ContainerInterface
     */
    protected $container = null;
    
    /**
     * @var ControllerResponse
     */
    protected $response = null;
    
    /**
     * @var string|null
     */
    protected $namespace;
    
    /**
     * @var string
     */
    protected $controllerClassName;
    
    /**
     * @var string
     */
    protected $actionName;
    
    /**
     * @var array
     */
    protected $params = [];
    
    /**
     * ControllerResolver constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->response = new ControllerResponse();
    }
    
    /**
     * @return ControllerResponse
     * @throws RuntimeWebAppException
     */
    public function execute()
    {
        $class = $this->getControllerClass();
        
        $reflectionClass = new \ReflectionClass($class);
        
        if ($reflectionClass->implementsInterface(ControllerInterface::class)) {
            /** @var Controller $controller */
            $controller = $reflectionClass->newInstance();
            $controller->setReflectionClass($reflectionClass);
            $this->getResponse()->setControllerInstance($controller);
            
            $controller->setContainer($this->container);
            
            $reflectionMethod = new \ReflectionMethod($class, $this->getActionName());
            $controller->setReflectionAction($reflectionMethod);
            
            $controller->setNamespace($this->getNamespace());
            $controller->setName($this->getControllerClassName());
            $controller->setAction($this->getActionName());
            $controller->setParams($this->getParams());
            
            $controller->beforeExecute();
            $this->response->setControllerContent($reflectionMethod->invokeArgs($controller, $this->getParams()));
            $controller->afterExecute();
            
        } else {
            throw new RuntimeWebAppException('Controller found but it should implemented interface [:name]', [
                'name' => ControllerInterface::class,
            ]);
        }
        
        return $this->response;
    }
    
    /**
     * @return string
     */
    public function getControllerClass()
    {
        return trim($this->getNamespace(), '\\') . '\\' . $this->getControllerClassName();
    }
    
    /**
     * @return null|string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    
    /**
     * @param null|string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
    
    /**
     * @return string
     */
    public function getControllerCamelize()
    {
        $className = $this->getControllerClassName();
        
        if (false !== strpos($className, 'Controller')) {
            $className = substr($className, 0, -10);
        }
        
        return ucfirst($className);
    }
    
    /**
     * @return string
     */
    public function getControllerClassName()
    {
        return $this->controllerClassName;
    }
    
    /**
     * @param string $controllerClassName
     */
    public function setControllerClassName($controllerClassName)
    {
        $this->controllerClassName = ucfirst($controllerClassName);
    }
    
    /**
     * @return ControllerResponse|null
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * @param ControllerResponse|null $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }
    
    /**
     * @return string
     */
    public function getActionCamelize()
    {
        $actionName = $this->getActionName();
        
        if (false !== strpos($actionName, 'Action')) {
            $actionName = substr($actionName, 0, -6);
        }
        
        return ucfirst($actionName);
    }
    
    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }
    
    /**
     * @param string $actionName
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;
    }
    
    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
    
    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }
    
}
