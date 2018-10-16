<?php

namespace Subapp\WebApp\Web\Html\Element;

class InputTextElement extends InputElement
{

  /**
   * InputTextElement constructor.
   *
   * @param       $name
   * @param null  $value
   * @param array $attributes
   */
  public function __construct($name, $value = null, array $attributes = [])
  {
    parent::__construct($name, $value, $attributes);
    $this->setAttribute('type', 'text');
  }

}