<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

class OptionElement extends HtmlElement
{

  /**
   * OptionElement constructor.
   *
   * @param null  $content
   * @param array $attributes
   */
  public function __construct($content = null, array $attributes = [])
  {
    parent::__construct('option', $attributes, null);
    $this->setContent($content);
  }

}
