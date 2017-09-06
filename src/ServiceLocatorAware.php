<?php

namespace Colibri\WebApp;

use Colibri\Parameters\ParametersCollection;
use Colibri\ServiceLocator\ContainerInterface;
use Colibri\Session\Flash\Flash\Session as FlashSession;
use Colibri\Http\Cookies;
use Colibri\Http\Request;
use Colibri\Http\Response;
use Colibri\Router\Router;
use Colibri\Session\Adapter\Files as SessionFiles;
use Colibri\Template\NullTemplate;
use Colibri\UrlGenerator\UrlBuilder;
use Composer\Autoload\ClassLoader;

/**
 * Interface ServiceLocatorAware
 *
 * @package Colibri\WebApp
 *
 * @property ParametersCollection config
 * @property ClassLoader classLoader
 * @property Request request
 * @property Response response
 * @property Cookies cookies
 * @property Router router
 * @property UrlBuilder url
 * @property NullTemplate view
 * @property SessionFiles session
 * @property FlashSession flash
 * @property NullTemplate template
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