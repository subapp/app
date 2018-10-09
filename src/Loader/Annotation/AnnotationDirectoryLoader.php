<?php

namespace Subapp\WebApp\Loader\Annotation;

use Subapp\Collection\ArrayCollection;
use Subapp\WebApp\Loader\DirectoryClassFileLoader;

/**
 * Class AnnotationDirectoryLoader
 * @package Subapp\WebApp\Loader\Annotation
 */
class AnnotationDirectoryLoader extends DirectoryClassFileLoader
{
    
    /**
     * @var AnnotationClassLoader
     */
    protected $classLoader;
    
    /**
     * AnnotationFileLoader constructor.
     * @param AnnotationClassLoader $classLoader
     */
    public function __construct(AnnotationClassLoader $classLoader)
    {
        $this->classLoader = $classLoader;
    }
    
    /**
     * @inheritDoc
     */
    public function load($resource, $resourceType)
    {
        $collection = new ArrayCollection();
        
        foreach (parent::load($resource, $resourceType) as $className) {
            if ($this->isSupported($className, null)) {
                $reflection = new \ReflectionClass($className);
                $this->classLoader->load($reflection, null);
            }
        }
        
        return $collection;
    }
    
    /**
     * @param $resource
     * @param $resourceType
     *
     * @return bool
     */
    public function isSupported($resource, $resourceType)
    {
        return class_exists($resource);
    }
  
}
