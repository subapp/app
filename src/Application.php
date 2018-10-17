<?php

namespace Subapp\WebApp;

use Subapp\Http\Response;
use Subapp\Router\Router;
use Subapp\ServiceLocator\ContainerInterface;
use Subapp\ServiceLocator\Service;
use Subapp\WebApp\Controller\Action\ClassMethodAction;
use Subapp\WebApp\Controller\ControllerClassMethod;
use Subapp\WebApp\Controller\Executor\ControllerActionExecutor;
use Subapp\WebApp\Controller\ExecutorFactory;
use Subapp\WebApp\Controller\Resolver;
use Subapp\WebApp\Controller\AbstractExecutor;
use Subapp\WebApp\Exception\NotFoundException;
use Subapp\WebApp\Exception\RouteNotFoundException;
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
     * @var callable
     */
    protected $fallbackAction;

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
     * @throws NotFoundException
     * @throws \Throwable
     */
    public function run()
    {
        $router = $this->initializeDefaultRoutes()->handle();
        
        if ($router->isFounded()) {

            $factory = new ExecutorFactory();
            
            list($class, $method) = $this->extractControllerCallback($router);

            $namespace = (null === $router->getNamespace())
                ? $this->getControllerNamespace() : $router->getNamespace();

            $action = new ControllerClassMethod($namespace, $class, $method);
            $action->setArguments($router->getMatches());
            $action->complementControllerInstance($this->getContainer());

            $executor = $factory->getExecutor($action);
            $executor->setAction($action);

            $resolver = new Resolver($this->response, $executor);
            $resolver->setCompiler($this->view);

            $this->templateInjection();
            
            try {
                $resolver->execute();
            } catch (\Throwable $exception) {
                $this->response->setStatusCode(500);
                throw $exception;
            }
            
        } else {
            $this->response->setStatusCode(404);
            throw new RouteNotFoundException($router);
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
     * @return callable
     */
    public function getFallbackAction()
    {
        return $this->fallbackAction;
    }

    /**
     * @param callable $fallbackAction
     */
    public function setFallbackAction(callable $fallbackAction)
    {
        $this->fallbackAction = $fallbackAction;
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
