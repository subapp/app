<?php

namespace Subapp\WebApp;

use Subapp\Http\Response;
use Subapp\Router\Router;
use Subapp\ServiceLocator\ContainerInterface;
use Subapp\ServiceLocator\Service;
use Subapp\WebApp\Controller\ActionExecutor;
use Subapp\WebApp\Controller\ControllerResolver;
use Subapp\WebApp\Exception\PageNotFoundException;
use Subapp\WebApp\Exception\RuntimeException;

/**
 * Class Application
 * @package Subapp\WebApp
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
     * @throws RuntimeException
     */
    public function __get($name)
    {
        if ($this->serviceLocator->has($name)) {
            return $this->serviceLocator->get($name);
        }
        
        throw new RuntimeException(sprintf('Cannot found service [%s] check if it registered', $name));
    }
    
    /**
     * @return Response
     * @throws PageNotFoundException
     * @throws \Throwable
     */
    public function run()
    {
        $router = $this->initializeDefaultRoutes()->handle();
        
        if ($router->isFounded()) {
            
            list($controllerName, $actionName) = $this->extractControllerCallback($router);
            
            $resolver = new ControllerResolver($this->getContainer());
            $executor = new ActionExecutor($this->response, $resolver);
            
            $executor->setCompiler($this->view);
            
            $namespace = null === $router->getNamespace()
                ? $this->getControllerNamespace()
                : $router->getNamespace();
            
            $resolver->setNamespace($namespace);
            $resolver->setControllerClassName($controllerName);
            $resolver->setActionName($actionName);
            $resolver->setParams($router->getMatches());
            
            $this->templateInjection();
            
            try {
                $executor->execute();
            } catch (\Throwable $exception) {
                $this->response->setStatusCode(500);
                throw $exception;
            }
            
        } else {
            $this->response->setStatusCode(404);
            
            throw new PageNotFoundException("Page with route '{$router->getTargetUri()}' was not found");
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
     * @param Router $router
     * @return array|callable
     */
    private function extractControllerCallback(Router $router)
    {
        return $router->hasCallback()
            ? $router->getCallback()
            : [ucfirst($router->getController()) . 'Controller', lcfirst($router->getAction()) . 'Action'];
    }
    
}
