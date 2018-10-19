<?php

namespace Subapp\WebApp\Controller;

use Subapp\ServiceLocator\ContainerInterface;
use Subapp\WebApp\Action\ClassMethodAction;

/**
 * Class ControllerAction
 * @package Subapp\WebApp\Controller
 */
class ControllerClassMethod extends ClassMethodAction
{

    /**
     * ControllerAction constructor.
     * @param $namespace
     * @param $class
     * @param $method
     */
    public function __construct($namespace, $class, $method)
    {
        parent::__construct($namespace, $class, $method);

        $this->getInstantiator()->setExpectedInterface(ControllerInterface::class);
    }

    /**
     * @param ContainerInterface $container
     */
    public function complementControllerInstance(ContainerInterface $container)
    {
        /** @var ControllerInterface $controller */
        $controller = $this->getObject();
        
        // set needed variables to controller object
        $controller->setContainer($container);
        $controller->setNamespace($this->getNamespace());
        $controller->setName($this->getClass());
        $controller->setAction($this->getMethod());
        $controller->setParams($this->getArguments());
    }

    /**
     * @return string
     */
    public function getShortClassName()
    {
        $class = $this->getClass();
        
        if (false !== strpos($class, 'Controller')) {
            $class = substr($class, 0, -10);
        }
        
        return ucfirst($class);
    }
    
    /**
     * @return string
     */
    public function getShortMethodName()
    {
        $method = $this->getMethod();
        
        if (false !== strpos($method, 'Action')) {
            $method = substr($method, 0, -6);
        }
        
        return ucfirst($method);
    }

}