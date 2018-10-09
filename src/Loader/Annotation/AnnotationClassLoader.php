<?php

namespace Subapp\WebApp\Loader\Annotation;

use Subapp\Annotations\Reader;
use Subapp\Loader\LoaderInterface;
use Subapp\Loader\LoaderResolverInterface;
use Subapp\WebApp\Annotation\AnnotationInterface;
use Subapp\WebApp\Exception\RuntimeException;

/**
 * Class AnnotationClassLoader
 * @package Subapp\WebApp\Loader\Annotation
 */
class AnnotationClassLoader implements LoaderInterface
{
    
    /**
     * @var Reader
     */
    protected $reader;
    
    protected $resolver;
    
    /**
     * AnnotationClassLoader constructor.
     * @param LoaderResolverInterface $resolver
     * @param Reader                  $reader
     */
    public function __construct(LoaderResolverInterface $resolver, Reader $reader)
    {
        $this->resolver = $resolver;
        $this->reader = $reader;
    }
    
    /**
     * @param $resource
     * @param $resourceType
     * @return void
     * @throws RuntimeException
     */
    public function load($resource, $resourceType)
    {
        if (!$resource instanceof \ReflectionClass) {
            throw new RuntimeException(sprintf('%s accept only %s class as resource',
                AnnotationClassLoader::class, \ReflectionClass::class));
        }
        
        foreach ($this->reader->getClassAnnotations($resource) as $annotation) {
            $this->configure($resource, $annotation);
        }
        
        foreach ($resource->getMethods() as $method) {
            foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
                $this->configure($method, $annotation);
            }
        }
        
        foreach ($resource->getProperties() as $property) {
            foreach ($this->reader->getPropertyAnnotations($property) as $annotation) {
                $this->configure($property, $annotation);
            }
        }
    }
    
    /**
     * @param \Reflector          $reflection
     * @param AnnotationInterface $annotation
     * @return void
     */
    public function configure(\Reflector $reflection, AnnotationInterface $annotation)
    {
        if (null !== ($loader = $this->resolver->resolve($annotation, null))) {
            $loader->load(new AnnotationResource($reflection, $annotation), null);
        }
    }
    
    /**
     * @param $resource
     * @param $resourceType
     * @return bool
     */
    public function isSupported($resource, $resourceType)
    {
        return $resource instanceof \ReflectionClass && (null === $resourceType || $resourceType === 'annotation');
    }
    
}