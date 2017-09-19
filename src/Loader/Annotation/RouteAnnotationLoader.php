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
   * @var string
   */
  protected $prefix;
  
  /**
   * @var string
   */
  protected $declaringClass;
  
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
      
      $className = $this->declaringClass;
      
      if ($reflection instanceof \ReflectionMethod) {
        $className = $reflection->getDeclaringClass()->getName();
        
        if ($this->declaringClass !== $className) {
          $this->setPrefix(null);
        }
        
        $this->addRoute($reflection, $annotation);
      } elseif ($reflection instanceof \ReflectionClass) {
        $this->setPrefix($annotation->prefix);
        $className = $reflection->getName();
      }
  
      $this->declaringClass = $className;
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
    
    $this->getRouter()->add($this->getPattern($annotation), $matches, $methods);
  }
  
  /**
   * @param Route $annotation
   * @return string
   */
  protected function getPattern(Route $annotation)
  {
    return sprintf('%s%s', $this->getPrefix(), $annotation->pattern);
  }
  
  /**
   * @return string
   */
  public function getPrefix()
  {
    return $this->prefix;
  }
  
  /**
   * @param string $prefix
   * @return $this
   */
  public function setPrefix($prefix)
  {
    $this->prefix = $prefix;
    
    return $this;
  }
  
  /**
   * @param \ReflectionMethod $method
   * @return array
   */
  protected function getRouteMatches(\ReflectionMethod $method)
  {
    return ['callback' => [$method->getDeclaringClass()->getShortName(), $method->getName()]];
  }
  
  /**
   * @return Router
   */
  public function getRouter()
  {
    return $this->serviceLocator->get('router');
  }
  
}
