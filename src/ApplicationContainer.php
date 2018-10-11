<?php

namespace Subapp\WebApp;

use Subapp\Collection\Parameters\ParametersCollection;
use Subapp\Http\Cookies;
use Subapp\Http\Request;
use Subapp\Http\Response;
use Subapp\Router\Router;
use Subapp\ServiceLocator\Container;
use Subapp\Session\Adapter\Files as SessionFiles;
use Subapp\Session\Flash\Flash\Session as FlashSession;
use Subapp\Template\NullTemplate;
use Subapp\UrlGenerator\UrlBuilder;
use Composer\Autoload\ClassLoader;

/**
 * Class ApplicationContainer
 * @package Subapp\WebApp
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
        $this->set('classLoader', new ClassLoader());
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