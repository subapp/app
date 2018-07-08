<?php

namespace Colibri\WebApp\Loader\Annotation;

use Colibri\Collection\ArrayCollection;
use Colibri\WebApp\Loader\DirectoryClassFileLoader;

/**
 * Class AnnotationDirectoryLoader
 * @package Colibri\WebApp\Loader\Annotation
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
            $reflection = new \ReflectionClass($className);
            $this->classLoader->load($reflection, null);
        }
        
        return $collection;
    }
    
}