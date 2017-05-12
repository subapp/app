<?php

namespace Colibri\WebApp;

use Colibri\Parameters\ParametersCollection;
use Colibri\ServiceLocator\Container;
use Colibri\Session\Flash\Flash\Session as FlashSession;
use Colibri\Http\Cookies;
use Colibri\Http\Request;
use Colibri\Http\Response;
use Colibri\Loader\Loader;
use Colibri\Router\Router;
use Colibri\Session\Adapter\Files as SessionFiles;
use Colibri\Template\NullTemplate;
use Colibri\UrlGenerator\UrlBuilder;

/**
 * @property ParametersCollection config
 * @property Loader loader
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
class FactoryContainer extends Container implements ServiceLocatorAware
{
  
  public function __construct()
  {
    parent::__construct();
  
    $this->set('config', new ParametersCollection());
    $this->set('loader', new Loader());
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