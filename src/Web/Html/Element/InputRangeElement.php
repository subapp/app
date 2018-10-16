<?php

namespace Subapp\WebApp\Web\Html\Element;

class InputRangeElement extends InputElement
{

  /**
   * InputRangeElement constructor.
   *
   * @param       $name
   * @param int   $min
   * @param int   $max
   * @param int   $step
   * @param null  $value
   * @param array $attributes
   */
  public function __construct($name, $min = 0, $max = 0, $step = 1, $value = null, array $attributes = [])
  {
    parent::__construct($name, $value, $attributes);
    $this->setAttributes([
      'type' => 'range',
      'min' => (int)$min,
      'max' => (int)$max,
      'step' => (int)$step,
    ]);
  }

}