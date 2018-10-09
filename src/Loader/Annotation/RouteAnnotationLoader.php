<?php

namespace Subapp\WebApp\Loader\Annotation;

use Subapp\Loader\LoaderInterface;
use Subapp\Router\Router;
use Subapp\ServiceLocator\ContainerInterface;
use Subapp\WebApp\Annotation\Route;

/**
 * Class RouteAnnotationLoader
 * @package Subapp\WebApp\Loader\Annotation
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
             * @var Route                              $annotation
             * @var \ReflectionMethod|\ReflectionClass $reflection
             */
            $annotation = $resource->getAnnotation();
            $reflection = $resource->getReflection();
            
            ($reflection instanceof \ReflectionMethod)
                ? $this->addRoute($reflection, $annotation) : $this->addPrefix($reflection, $annotation);
        }
    }
    
    /**
     * @param \ReflectionClass $class
     * @param Route            $annotation
     */
    protected function addPrefix(\ReflectionClass $class, Route $annotation)
    {
        $this->setPrefix($annotation->prefix);
        $this->setDeclaringClass($class->getName());
    }
    
    /**
     * @param \ReflectionMethod $method
     * @param Route             $annotation
     */
    protected function addRoute(\ReflectionMethod $method, Route $annotation)
    {
        $class = $method->getDeclaringClass();
        
        if ($this->getDeclaringClass() !== $class->getName()) {
            $this->setPrefix(null);
            $this->setDeclaringClass($class->getName());
        }
        
        $methods = $annotation->methods ?: [];
        $matches = $this->getRouteMatches($method);
        
        $route = $this->getRouter()->add($this->getPattern($annotation), $matches, $methods);
        
        if (null !== $annotation->regexp) {
            $regexPairs = array_chunk($annotation->regexp, 2);
            foreach ($regexPairs as list($variable, $regex)) {
                $route->regex($variable, $regex);
            }
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
     * @return string
     */
    public function getDeclaringClass()
    {
        return $this->declaringClass;
    }
    
    /**
     * @param string $declaringClass
     * @return $this
     */
    public function setDeclaringClass($declaringClass)
    {
        $this->declaringClass = $declaringClass;
        
        return $this;
    }
    
    /**
     * @param \ReflectionMethod $method
     * @return array
     */
    protected function getRouteMatches(\ReflectionMethod $method)
    {
        $class = $method->getDeclaringClass();
        
        return [
            'callback' => [$class->getShortName(), $method->getName()],
            'namespace' => $class->getNamespaceName(),
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
