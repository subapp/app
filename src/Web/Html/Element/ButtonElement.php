<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

class ButtonElement extends HtmlElement
{

  /**
   * ButtonElement constructor.
   *
   * @param       $name
   * @param mixed $value
   * @param array $attributes
   */
  public function __construct($name, $value = null, array $attributes = [])
  {
    parent::__construct('button', $attributes, null);
    $this->setAttribute('name', $name)->setContent($value);
  }

}