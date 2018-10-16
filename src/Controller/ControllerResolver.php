<?php

namespace Subapp\WebApp\Controller;

use Subapp\ServiceLocator\ContainerInterface;
use Subapp\WebApp\Controller;
use Subapp\WebApp\Exception\ClassNotFoundException;
use Subapp\WebApp\Exception\MethodNotFoundException;
use Subapp\WebApp\Exception\NotFoundException;
use Subapp\WebApp\Exception\RuntimeException;

/**
 * Class ControllerResolver
 * @package Subapp\WebApp\Controller
 */
class ControllerResolver
{
    
    /**
     * @var ContainerInterface
     */
    protected $container = null;
    
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
    }
    
    /**
     * @inheritdoc
     */
    public function __debugInfo()
    {
        return [
            'callback' => sprintf('%s\%s::%s(%s);',
                $this->namespace, $this->controllerClassName, $this->actionName, implode(', ', $this->params)),
        ];
    }
    
    /**
     * @return ControllerResponse
     */
    public function execute()
    {
        $response = new ControllerResponse();

        $class = $this->getControllerClass();
        $controller = $this->getControllerInstance($class);

        $this->complementControllerInstance($controller);

        $method = $this->getControllerMethodInstance($controller, $this->getActionName());

        $controller->beforeExecute();
        $response->setControllerContent($method->invokeArgs($controller, $this->getParams()));
        $controller->afterExecute();

        $response->setControllerInstance($controller);

        return $response;
    }

    /**
     * @param ControllerInterface $controller
     */
    public function complementControllerInstance(ControllerInterface $controller)
    {
        $controller->setContainer($this->container);

        $controller->setNamespace($this->getNamespace());
        $controller->setName($this->getControllerClassName());
        $controller->setAction($this->getActionName());
        $controller->setParams($this->getParams());
    }

    /**
     * @param ControllerInterface $controller
     * @param $name
     * @return \ReflectionMethod
     * @throws MethodNotFoundException
     */
    public function getControllerMethodInstance(ControllerInterface $controller, $name)
    {
        $class = $controller->getReflectionClass();

        try {
            $method = $class->getMethod($name);
        } catch (\ReflectionException $exception) {
            throw new MethodNotFoundException($class->getName(), $name);
        }

        return $method;
    }

    /**
     * @param string $class
     * @return ControllerInterface
     */
    public function getControllerInstance($class)
    {
        $reflection = $this->getControllerReflection($class);

        /** @var ControllerInterface $controller */
        $controller = $reflection->newInstance();
        $controller->setReflectionClass($reflection);

        return $controller;
    }

    /**
     * @param $class
     * @return \ReflectionClass
     * @throws ClassNotFoundException|\Throwable
     */
    public function getControllerReflection($class)
    {
        try {
            $reflection = new \ReflectionClass($class);
            if (!$reflection->implementsInterface(ControllerInterface::class)) {
                throw new NotFoundException(sprintf(
                    'Irregular controller class name. Because it doesn\'t implement interface %s',
                    ControllerInterface::class));
            }
        } catch (\ReflectionException $exception) {
            throw new ClassNotFoundException($class);
        } catch (\Throwable $exception) {
            throw $exception;
        }

        return $reflection;
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
