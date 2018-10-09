<?php

namespace Subapp\WebApp\Loader\Annotation;

use Subapp\WebApp\Annotation\AnnotationInterface;
use Subapp\WebApp\Loader\ResourceInterface;

/**
 * Class AnnotationResource
 * @package Subapp\WebApp\Annotation
 */
class AnnotationResource implements ResourceInterface
{
    
    /**
     * @var \Reflector
     */
    protected $reflection;
    
    /**
     * @var AnnotationInterface
     */
    protected $annotation;
    
    /**
     * AnnotationResource constructor.
     * @param \Reflector          $reflection
     * @param AnnotationInterface $annotation
     */
    public function __construct(\Reflector $reflection, AnnotationInterface $annotation)
    {
        $this->reflection = $reflection;
        $this->annotation = $annotation;
    }
    
    /**
     * @return \Reflector
     */
    public function getReflection()
    {
        return $this->reflection;
    }
    
    /**
     * @return mixed
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }
    
}