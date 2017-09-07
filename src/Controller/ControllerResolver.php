<?php

namespace Colibri\WebApp\Controller;

use Colibri\ServiceLocator\ContainerInterface;
use Colibri\WebApp\Controller;
use Colibri\WebApp\Exception\RuntimeWebAppException;
use Colibri\WebApp\WebAppException;

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
   * @throws WebAppException
   */
  public function execute()
  {
    $class = $this->getControllerClass();
    
    try {
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
        $controller->setName($this->getControllerClass());
        $controller->setAction($this->getActionName());
        $controller->setParams($this->getParams());
        
        $controller->beforeExecute();
        $this->response->setControllerContent($reflectionMethod->invokeArgs($controller, $this->getParams()));
        $controller->afterExecute();

      } else {
        throw new RuntimeWebAppException('Controller found but it should implemented interface [:name]', [
          'name' => ControllerInterface::class
        ]);
      }
      
    } catch (\ReflectionException $exception) {
      throw new WebAppException($exception->getMessage());
    } catch (\Exception $exception) {
      throw new RuntimeWebAppException($exception->getMessage());
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
    return ucfirst(rtrim($this->getControllerClassName(), 'Controller'));
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
    return ucfirst(rtrim($this->getActionName(), 'Action'));
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
