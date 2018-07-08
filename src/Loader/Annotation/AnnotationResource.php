<?php

namespace Colibri\WebApp\Loader\Annotation;

use Colibri\WebApp\Annotation\AnnotationInterface;
use Colibri\WebApp\Loader\ResourceInterface;

/**
 * Class AnnotationResource
 * @package Colibri\WebApp\Annotation
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