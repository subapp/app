<?php

namespace Colibri\WebApp\ServiceLocator;

use Colibri\Http\Cookies;
use Colibri\Http\Request;
use Colibri\Http\Response;
use Colibri\Logger\Log;
use Colibri\Parameters\ParametersCollection;
use Colibri\Router\Router;
use Colibri\ServiceContainer\ServiceLocator;
use Colibri\ServiceLocator\ContainerInterface;
use Colibri\Session\Adapter\Files as SessionFiles;
use Colibri\Session\Flash\Flash\Session as FlashSession;
use Colibri\Template\NullTemplate;
use Colibri\UrlGenerator\UrlBuilder;
use Colibri\WebApp\Auth\AuthInterface;
use Colibri\WebApp\Exception\RuntimeException;
use Colibri\WebApp\Web\Assets;
use Colibri\WebApp\Web\Metatag;
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
 * @package Colibri\Webapp\ServiceLocator
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
    public function getColibri()
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