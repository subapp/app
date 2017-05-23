<?php

namespace Colibri\WebApp\Annotation;

use Colibri\Annotations\Annotation\Target;
use Colibri\Router\Router;

/**
 * Class Route
 * @package Colibri\WebApp\Annotation
 * @Annotation()
 * @Target({Target::CLAZZ, Target::METHOD})
 */
class Route implements AnnotationInterface
{
  
  /**
   * @var string
   */
  public $pattern;
  
  /**
   * @var string
   */
  public $controller;
  
  /**
   * @var string
   */
  public $action;
  
  /**
   * @var array
   */
  public $methods;
  
  /**
   * @var array
   */
  public $regexp;
  
}