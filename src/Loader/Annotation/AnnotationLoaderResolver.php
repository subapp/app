<?php

namespace Colibri\WebApp\Loader\Annotation;

use Colibri\Loader\LoaderInterface;
use Colibri\Loader\LoaderResolverInterface;

/**
 * Class AnnotationLoaderResolver
 * @package Colibri\WebApp\Loader\Annotation
 */
class AnnotationLoaderResolver implements LoaderResolverInterface
{
    
    /**
     * @var LoaderInterface[]
     */
    protected $loaders = [];
    
    /**
     * AnnotationLoaderResolver constructor.
     * @param array $loaders
     */
    public function __construct(array $loaders = [])
    {
        foreach ($loaders as $loader) {
            $this->addLoader($loader);
        }
    }
    
    /**
     * @param LoaderInterface $loader
     */
    public function addLoader(LoaderInterface $loader)
    {
        $this->loaders[] = $loader;
    }
    
    /**
     * @inheritdoc
     */
    public function resolve($resource, $resourceType)
    {
        foreach ($this->loaders as $loader) {
            if ($loader->isSupported($resource, $resourceType)) {
                return $loader;
            }
        }
        
        return null;
    }
    
}