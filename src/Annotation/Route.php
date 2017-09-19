<?php

namespace Colibri\WebApp\Annotation;

use Colibri\Annotations\Annotation\Target;

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
  public $prefix;
  
  /**
   * @var string
   */
  public $pattern;
  
  /**
   * @var array
   */
  public $regexp;
  
  /**
   * @var array
   */
  public $methods;
  
}
