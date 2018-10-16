<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

class LabelElement extends HtmlElement
{

  /**
   * LabelElement constructor.
   *
   * @param       $content
   * @param array $attributes
   */
  public function __construct($content = null, array $attributes = [])
  {
    parent::__construct('label', $attributes, null);
    $this->setSingle(false)->setContent($content);
  }

}