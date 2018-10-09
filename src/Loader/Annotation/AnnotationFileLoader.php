<?php

namespace Subapp\WebApp\Loader\Annotation;

use Subapp\WebApp\Loader\ClassFileLoader;

/**
 * Class AnnotationFileLoader
 * @package Subapp\WebApp\Loader\Annotation
 */
class AnnotationFileLoader extends ClassFileLoader
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
        $reflection = new \ReflectionClass(parent::load($resource, $resourceType));
        
        return $this->classLoader->load($reflection, null);
    }
    
}