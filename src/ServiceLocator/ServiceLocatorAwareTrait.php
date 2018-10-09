<?php

namespace Subapp\WebApp\ServiceLocator;

use Subapp\Http\Cookies;
use Subapp\Http\Request;
use Subapp\Http\Response;
use Subapp\Logger\Log;
use Subapp\Parameters\ParametersCollection;
use Subapp\Router\Router;
use Subapp\ServiceContainer\ServiceLocator;
use Subapp\ServiceLocator\ContainerInterface;
use Subapp\Session\Adapter\Files as SessionFiles;
use Subapp\Session\Flash\Flash\Session as FlashSession;
use Subapp\Template\NullTemplate;
use Subapp\UrlGenerator\UrlBuilder;
use Subapp\WebApp\Auth\AuthInterface;
use Subapp\WebApp\Exception\RuntimeException;
use Subapp\WebApp\Web\Assets;
use Subapp\WebApp\Web\Metatag;
use Composer\Autoload\ClassLoader;

/**
 * @property ParametersCollection config
 * @property ClassLoader          classLoader
 * @property Request              request
 * @property Response             response
 * @property Cookies              cookies
 * @property Router               router
 * @property UrlBuilder           url
 * @property NullTemplate         view
 * @property SessionFiles         session
 * @property FlashSession         flash
 * @property NullTemplate         template
 * @property ServiceLocator       colibri
 * @property Metatag              metatag
 * @property Log                  logger
 * @property AuthInterface        auth
 * @property Assets               assets
 *
 * Trait ServiceLocatorAwareTrait
 * @package Subapp\Webapp\ServiceLocator
 */
trait ServiceLocatorAwareTrait
{
    
    /**
     * @param $name
     * @return null|object
     * @throws RuntimeException
     */
    public function __get($name)
    {
        $container = $this->getContainer();
        
        if (null === $container) {
            throw new RuntimeException(sprintf('Helper %s not initialized with container %s yet',
                __TRAIT__, ContainerInterface::class));
        }
        
        if ($container->offsetExists($name)) {
            return $container->offsetGet($name);
        }
        
        throw new RuntimeException(sprintf('Helper %s could not found service with name %s',
            __TRAIT__, $name));
    }
    
    /**
     * @return ContainerInterface
     */
    abstract public function getContainer();
    
    /**
     * @param ContainerInterface $container
     * @return $this
     */
    abstract public function setContainer(ContainerInterface $container);
    
    /**
     * @return ParametersCollection
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * @return ClassLoader
     */
    public function getClassLoader()
    {
        return $this->classLoader;
    }
    
    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * @return Cookies
     */
    public function getCookies()
    {
        return $this->cookies;
    }
    
    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }
    
    /**
     * @return UrlBuilder
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * @return NullTemplate
     */
    public function getView()
    {
        return $this->view;
    }
    
    /**
     * @return SessionFiles
     */
    public function getSession()
    {
        return $this->session;
    }
    
    /**
     * @return FlashSession
     */
    public function getFlash()
    {
        return $this->flash;
    }
    
    /**
     * @return NullTemplate
     */
    public function getTemplate()
    {
        return $this->template;
    }
    
    /**
     * @return ServiceLocator
     */
    public function getSubapp()
    {
        return $this->colibri;
    }
    
    /**
     * @return Metatag
     */
    public function getMetatag()
    {
        return $this->metatag;
    }
    
    /**
     * @return Log
     */
    public function getLogger()
    {
        return $this->logger;
    }
    
    /**
     * @return AuthInterface
     */
    public function getAuth()
    {
        return $this->auth;
    }
    
    /**
     * @return Assets
     */
    public function getAssets()
    {
        return $this->assets;
    }
    
}