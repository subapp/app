<?php

namespace Subapp\WebApp\Loader\Annotation;

use Subapp\Loader\LoaderInterface;
use Subapp\Loader\LoaderResolverInterface;

/**
 * Class AnnotationLoaderResolver
 * @package Subapp\WebApp\Loader\Annotation
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