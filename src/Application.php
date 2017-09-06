<?php

namespace Colibri\WebApp;

use Colibri\Parameters\ParametersCollection;
use Colibri\Http\Response;
use Colibri\ServiceLocator\ContainerInterface;
use Colibri\ServiceLocator\Service;
use Colibri\WebApp\Controller\ControllerResolver;
use Colibri\Router\Router;

/**
 * Class Application
 * @package Colibri\WebApp
 */
class Application implements ServiceLocatorAware
{
  
  /**
   * @var ContainerInterface
   */
  protected $serviceLocator;
  
  /**
   * @var string
   */
  protected $controllerNamespace = '\\App\\Controller\\';
  
  /**
   * Application constructor.
   */
  public function __construct()
  {
    $this->setContainer(ApplicationContainer::instance());
  }

  /**
   * @return ContainerInterface
   */
  public function getContainer()
  {
    return $this->serviceLocator;
  }

  /**
   * @param ContainerInterface $container
   */
  public function setContainer(ContainerInterface $container)
  {
    $this->serviceLocator = $container;
  }

  /**
   * @param $name
   * @return mixed
   * @throws WebAppException
   */
  public function __get($name)
  {
    if ($this->serviceLocator->has($name)) {
      return $this->serviceLocator->get($name);
    }
  
    throw new WebAppException(sprintf('Cannot found service [%s] check if it registered', $name));
  }
  
  /**
   * @return ParametersCollection|\Colibri\Parameters\ParametersInterface
   */
  public static function sampleConfiguration()
  {
    $configurationFilePath = realpath(__DIR__ . '/../sample.configuration.php');
    
    $config = ParametersCollection::createFromFile($configurationFilePath);
    $config['sample_config_file'] = $configurationFilePath;
    
    return $config;
  }
  
  /**
   * @return Response
   * @throws WebAppException
   * @throws \Exception
   */
  public function run()
  {
    $router = $this->initializeDefaultRoutes()->handle();

    if ($router->isFounded()) {
      
      $resolver = new ControllerResolver($this->getContainer());
      
      $namespace = null === $router->getNamespace()
        ? $this->getControllerNamespace()
        : $router->getNamespace();
      
      $resolver->setNamespace($namespace);
      $resolver->setController($router->getController());
      $resolver->setAction($router->getAction());
      $resolver->setParams($router->getMatches());
  
      $this->templateInjection();
      
      try {
        
        $response = $resolver->execute();
        
        // If content body is disabled for response, nothing to set in it
        // Used in case with redirect, for example
        if ($this->response->isEnableBody() === true) {
  
          $content = $response->getControllerContent();
          $controller = $response->getControllerInstance();
          
          // Special condition for handle controller templates
          if ($this->response->getBodyFormat() == Response::RESPONSE_HTML) {
            // If controller action nothing return, we try render inner template
            // controller_name/action_name.php
            if (null === $content) {
              $templatePath = "{$resolver->getControllerCamelize()}/{$resolver->getActionCamelize()}";
              $content = $this->render($templatePath, $controller->getPseudoPath());
            }
            
            // If controller has main layout trying, we try wrap in it
            if (null !== $controller->getLayout()) {
              $content = $this->render($controller->getLayout(), null, ['content' => $content]);
            }
          }
          
          $this->response->setContent($content);
        }
        
      } catch (WebAppException $exception) {
        $this->response->setStatusCode(404);
        throw $exception;
      } catch (\Exception $exception) {
        $this->response->setStatusCode(500);
        throw $exception;
      }
      
    } else {
      $this->response->setStatusCode(404);
      
      throw new WebAppException("Page with route '{$router->getTargetUri()}' was not found");
    }

    return $this->response->send();
  }
  
  /**
   * @return Router
   */
  protected function initializeDefaultRoutes()
  {
    $this->router->add('/');
    $this->router->add('/:controller');
    $this->router->add('/:controller/:id');
    $this->router->add('/:controller/:action');
    $this->router->add('/:controller/:action/:id');
    $this->router->add('/:controller/:action/:params');
    
    return $this->router;
  }
  
  /**
   * @return string
   */
  public function getControllerNamespace()
  {
    return $this->controllerNamespace;
  }
  
  /**
   * @param string $controllerNamespace
   * @return static
   */
  public function setControllerNamespace($controllerNamespace)
  {
    $this->controllerNamespace = $controllerNamespace;
    
    return $this;
  }
  
  /**
   * @return $this
   */
  protected function templateInjection()
  {
    /** @var Service $service */
    foreach ($this->getContainer() as $service) {
      $this->view->set($service->getName(), $this->{$service->getName()});
    }
    
    return $this;
  }
  
  /**
   * @param string $path
   * @param string $pseudoPath
   * @param array $data
   * @return string
   */
  protected function render($path, $pseudoPath, array $data = [])
  {
    $content = $this->view->fetch(null === $pseudoPath ? $path : "$pseudoPath::$path", $data);
    
    return $content;
  }
  
}
