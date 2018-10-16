<?php

namespace Subapp\WebApp\Web\Html\Element;

/**
 * Class InputSubmitElement
 * @package Subapp\WebApp\Web\Html\Element
 */
class InputSubmitElement extends InputElement
{

  /**
   * SubmitElement constructor.
   *
   * @param       $value
   * @param null  $name
   * @param array $attributes
   */
  public function __construct($value, $name = null, array $attributes = [])
  {
    parent::__construct($name, $value, $attributes);
    
    $this->setAttribute('type', 'submit');
  }

}