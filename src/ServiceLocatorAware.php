<?php

namespace Subapp\WebApp;

use Subapp\Http\Cookies;
use Subapp\Http\Request;
use Subapp\Http\Response;
use Subapp\Parameters\ParametersCollection;
use Subapp\Router\Router;
use Subapp\ServiceLocator\ContainerInterface;
use Subapp\Session\Adapter\Files as SessionFiles;
use Subapp\Session\Flash\Flash\Session as FlashSession;
use Subapp\Template\NullTemplate;
use Subapp\UrlGenerator\UrlBuilder;
use Composer\Autoload\ClassLoader;

/**
 * Interface ServiceLocatorAware
 *
 * @package Subapp\WebApp
 *
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
 */
interface ServiceLocatorAware
{
    
    /**
     * @return ContainerInterface|ServiceLocatorAware
     */
    public function getContainer();
    
    /**
     * @param ContainerInterface|ServiceLocatorAware $container
     */
    public function setContainer(ContainerInterface $container);
    
}