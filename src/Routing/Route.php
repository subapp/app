<?php

namespace Colibri\WebApp\Routing;

use Colibri\Annotations\Annotation\Target;
use Colibri\Router\Router;

/**
 * Class Route
 * @package Colibri\WebApp\Routing
 * @Annotation()
 * @Target({Target::CLAZZ, Target::METHOD})
 */
class Route
{
  
  /**
   * @var string
   */
  public $pattern;
  
  /**
   * @var array
   */
  public $matches = [];
  
  /**
   * @var array
   */
  public $methods = [];
  
  /**
   * @param Router $router
   */
  public function register(Router $router)
  {
    $router->add($this->pattern, $this->matches, $this->methods);
  }
  
}