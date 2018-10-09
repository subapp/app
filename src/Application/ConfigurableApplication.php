<?php

namespace Subapp\WebApp\Application;

use Subapp\Annotations\Reader;
use Subapp\Parameters\ParametersInterface;
use Subapp\Template\Template;
use Subapp\WebApp\Application;
use Subapp\WebApp\Loader\Annotation\AnnotationClassLoader;
use Subapp\WebApp\Loader\Annotation\AnnotationDirectoryLoader;
use Subapp\WebApp\Loader\Annotation\AnnotationLoaderResolver;
use Subapp\WebApp\Loader\Annotation\RouteAnnotationLoader;

/**
 * Class ConfigurableApplication
 * @package Subapp\WebApp\Application
 */
abstract class ConfigurableApplication extends Application
{
    
    /**
     * ConfigurableApplication constructor.
     * @param ParametersInterface $parameters
     */
    public function __construct(ParametersInterface $parameters)
    {
        parent::__construct();
        
        $this->config->merge($parameters);
    }
    
    /**
     * @return $this
     */
    public function configure()
    {
        $this->config->handlePlaceholders();
        
        $container = $this->getContainer();
        
        $configuration = new ApplicationConfiguration($this->config->toArray());
        $configuration->initialize();
        
        $container->set('config', $configuration);
        
        // configure required server values
        date_default_timezone_set($configuration->getTimezone());
        
        error_reporting($configuration->getErrorLevel());
        ini_set('display_errors', $configuration->getDisplayErrors());
        
        // configure application
        $this->setControllerNamespace($configuration->getControllerNS());
        
        // URI configure
        $this->url->setBasePath($configuration->getURIBasePath());
        $this->url->setStaticPath($configuration->getURIStaticPath());
        
        if ($directory = $configuration->getTemplateDirectory()) {
            $this->serviceLocator->set('view', new Template($directory, iterator_to_array($this->serviceLocator)));
        }
        
        $this->loadControllersAnnotations();
        
        $this->boot();
        
        return $this;
    }
    
    /**
     * @return $this
     */
    protected function loadControllersAnnotations()
    {
        if ($this->config->path('annotations.enabled')
            && null !== ($directory = $this->config->path('annotations.controllers.directory'))
            && false !== ($directory = realpath($directory))) {
            
            $reader = new Reader();
            $parser = $reader->getParser();
            $parser->addNamespace('Subapp\\WebApp\\Annotation');
            
            if (is_iterable($namespaces = $this->config->path('annotations.namespaces'))) {
                foreach ($namespaces as $namespace) {
                    $parser->addNamespace($namespace);
                }
            }
            
            $resolver = new AnnotationLoaderResolver();
            $resolver->addLoader(new RouteAnnotationLoader($this->getContainer()));
            
            $loader = new AnnotationDirectoryLoader(new AnnotationClassLoader($resolver, $reader));
            $loader->load($directory, null);
        }
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSystemTemplateDirectory()
    {
        return __DIR__ . '/../SystemTemplate';
    }
    
    /**
     * @return \Subapp\Http\Response
     */
    public function run()
    {
        $this->configure();
        
        return parent::run();
    }
    
    /**
     * @return $this
     */
    abstract protected function boot();
    
}
