<?php

namespace Colibri\WebApp\Application;

use Colibri\Annotations\Reader;
use Colibri\Parameters\ParametersCollection;
use Colibri\Parameters\ParametersInterface;
use Colibri\WebApp\Application;
use Colibri\Template\Template;
use Colibri\WebApp\Loader\Annotation\AnnotationClassLoader;
use Colibri\WebApp\Loader\Annotation\AnnotationDirectoryLoader;
use Colibri\WebApp\Loader\Annotation\AnnotationLoaderResolver;
use Colibri\WebApp\Loader\Annotation\RouteAnnotationLoader;
use Colibri\WebApp\WebAppException;

/**
 * Class ConfigurableApplication
 * @package Colibri\WebApp\Application
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
   * @throws WebAppException
   */
  public function configure()
  {
    /**
     * @var ParametersCollection $serverConfig
     * @var ParametersCollection $applicationConfig
     */
  
    $this->config->handlePlaceholders();
    
    if ($this->config->offsetExists('server')) {
      $serverConfig = $this->config->get('server');
      
      if ($serverConfig->offsetExists('timezone')) {
        date_default_timezone_set($serverConfig['timezone']);
      }
      
      if ($serverConfig->offsetExists('displayErrors')) {
        ini_set('display_errors', $serverConfig['displayErrors']);
      }
      
      if ($serverConfig->offsetExists('errorLevel')) {
        error_reporting($serverConfig['errorLevel']);
      }
    }
    
    if ($this->config->offsetExists('application')) {
      $applicationConfig = $this->config->get('application');
      
      if ($applicationConfig->offsetExists('autoload')) {
        $this->loader->registerNamespaces($applicationConfig->get('autoload')->toArray())
          ->register();
      }
      
      if ($applicationConfig->offsetExists('controller')) {
        $this->setControllerNamespace($applicationConfig['controller']['namespace']);
      }
      
      if ($applicationConfig->offsetExists('base_path')) {
        $this->url->setBasePath($applicationConfig['base_path']);
        if ($applicationConfig->offsetExists('static_path')) {
          $this->url->setStaticPath($applicationConfig['static_path']);
        }
      }
      
      if ($applicationConfig->offsetExists('view')) {
        $this->serviceLocator->set('view', function () use ($applicationConfig) {
          $services = iterator_to_array($this->serviceLocator);
          $directory = $applicationConfig['view']['root_directory'];
          return new Template($directory, $services);
        });
      } else {
        throw new WebAppException('Required configuration for view don`ts exists');
      }
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
      && ($directory = realpath($this->config->path('annotations.controllers.directory')))) {
    
      $reader = new Reader();
      $parser = $reader->getParser();
      $parser->addNamespace('Colibri\\WebApp\\Annotation');
  
      $resolver = new AnnotationLoaderResolver();
      $resolver->addLoader(new RouteAnnotationLoader($this->getContainer()));
      
      $loader = new AnnotationDirectoryLoader(new AnnotationClassLoader($resolver, $reader));
      $loader->load($directory, null);
    }
    
    return $this;
  }
  
  /**
   * @return \Colibri\Http\Response
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
