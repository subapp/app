<?php

namespace Colibri\WebApp\Loader\Annotation;

use Colibri\Loader\LoaderInterface;
use Colibri\Router\Router;
use Colibri\ServiceLocator\ContainerInterface;
use Colibri\WebApp\Annotation\Route;

/**
 * Class RouteAnnotationLoader
 * @package Colibri\WebApp\Loader\Annotation
 */
class RouteAnnotationLoader implements LoaderInterface
{
  
  /**
   * @var ContainerInterface
   */
  protected $serviceLocator;
  
  /**
   * RouteAnnotationLoader constructor.
   * @param ContainerInterface $serviceLocator
   */
  public function __construct(ContainerInterface $serviceLocator)
  {
    $this->serviceLocator = $serviceLocator;
  }
  
  /**
   * @inheritDoc
   */
  public function load($resource, $resourceType)
  {
    if ($resource instanceof AnnotationResource) {
      /**
       * @var Route $annotation
       * @var \ReflectionMethod $reflection
      */
      $annotation = $resource->getAnnotation();
      $reflection = $resource->getReflection();

      $this->addRoute($reflection, $annotation);
    }
  }
  
  /**
   * @inheritDoc
   */
  public function isSupported($resource, $resourceType)
  {
    return $resource instanceof Route;
  }
  
  /**
   * @param \ReflectionMethod $method
   * @param Route $annotation
   */
  protected function addRoute(\ReflectionMethod $method, Route $annotation)
  {
    $methods = $annotation->methods ?: [];
    $matches = $this->getRouteMatches($method);
    
    $this->getRouter()->add($annotation->pattern, $matches, $methods);
  }
  
  /**
   * @param \ReflectionMethod $method
   * @return array
   */
  protected function getRouteMatches(\ReflectionMethod $method)
  {
    $controller = str_replace('Controller', null, $method->getDeclaringClass()->getShortName());
    $action = str_replace('Action', null, $method->getName());
    
    return [
      'controller' => strtolower($controller),
      'action' => strtolower($action),
    ];
  }
  
  /**
   * @return Router
   */
  public function getRouter()
  {
    return $this->serviceLocator->get('router');
  }
  
}