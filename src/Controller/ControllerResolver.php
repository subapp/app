<?php

namespace Colibri\WebApp\Controller;

use Colibri\ServiceLocator\ContainerInterface;
use Colibri\WebApp\Controller;

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
  protected $controller;
  
  /**
   * @var string
   */
  protected $action;
  
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
   * @throws Page404Exception
   * @throws RuntimeMvcException
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
        
        $reflectionMethod = new \ReflectionMethod($class, $this->getActionCamelize());
        $controller->setReflectionAction($reflectionMethod);
        
        $controller->setNamespace($this->getNamespace());
        $controller->setName($this->getController());
        $controller->setAction($this->getAction());
        $controller->setParams($this->getParams());
        
        $controller->beforeExecute();
        $this->response->setControllerContent($reflectionMethod->invokeArgs($controller, $this->getParams()));
        $controller->afterExecute();

      } else {
        throw new RuntimeMvcException('Controller found but it should implemented interface [:name]', [
          'name' => ControllerInterface::class
        ]);
      }
      
    } catch (\ReflectionException $exception) {
      throw new Page404Exception($exception->getMessage());
    } catch (\Exception $exception) {
      throw new RuntimeMvcException($exception->getMessage());
    }
    
    return $this->response;
  }
  
  /**
   * @return string
   */
  public function getControllerClass()
  {
    return trim($this->getNamespace(), '\\') . '\\' . $this->getControllerCamelize();
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
    $parts = preg_split('/[-_]+/uis', $this->getController());
    
    return implode('', array_map('ucfirst', $parts)) . 'Controller';
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
    $parts = preg_split('/[-_]+/uis', $this->getAction());
    
    return lcfirst(implode('', array_map('ucfirst', $parts))) . 'Action';
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