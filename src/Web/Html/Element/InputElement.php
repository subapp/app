<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

/**
 * Class InputElement
 *
 * @package Subapp\WebApp\Web\Html\Element
 */
abstract class InputElement extends HtmlElement
{

  /**
   * InputElement constructor.
   *
   * @param       $name
   * @param null  $value
   * @param array $attributes
   */
  public function __construct($name, $value = null, array $attributes = [])
  {
    parent::__construct('input', $attributes, null);

    $this->setSingle(true)->setAttribute('name', $name)->setAttribute('value', $value);
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->getAttribute('name');
  }
  
  /**
   * @param $name
   * @return $this
   */
  public function setName($name)
  {
    $this->setAttribute('name', $name);

    return $this;
  }
  
  /**
   * @return string
   */
  public function getValue()
  {
    return $this->getAttribute('value');
  }
  
  /**
   * @param $value
   * @return $this
   */
  public function setValue($value = null)
  {
    $this->setAttribute('value', $value);
    
    return $this;
  }

}