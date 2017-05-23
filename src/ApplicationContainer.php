<?php

namespace Colibri\WebApp;

use Colibri\Loader\ClassLoader\ClassLoader;
use Colibri\Parameters\ParametersCollection;
use Colibri\ServiceLocator\Container;
use Colibri\Session\Flash\Flash\Session as FlashSession;
use Colibri\Http\Cookies;
use Colibri\Http\Request;
use Colibri\Http\Response;
use Colibri\Router\Router;
use Colibri\Session\Adapter\Files as SessionFiles;
use Colibri\Template\NullTemplate;
use Colibri\UrlGenerator\UrlBuilder;

/**
 * Class ApplicationContainer
 * @package Colibri\WebApp
 */
class ApplicationContainer extends Container implements ServiceLocatorAware
{
  
  /**
   * ApplicationContainer constructor.
   */
  public function __construct()
  {
    parent::__construct();
  
    $this->set('config', new ParametersCollection());
    $this->set('loader', new ClassLoader());
    $this->set('request', new Request());
    $this->set('response', new Response());
    $this->set('cookies', new Cookies());
    $this->set('router', new Router($this->get('request')));
    $this->set('url', new UrlBuilder($this->get('router'), $this->get('request')));
    $this->set('view', new NullTemplate());
    $this->set('session', new SessionFiles());
    $this->set('flash', new FlashSession($this->get('session')));
    $this->set('template', $this->get('view'));
  }
  
}