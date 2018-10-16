<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

class ItalicElement extends HtmlElement
{

  /**
   * ItalicElement constructor.
   *
   * @param null  $content
   * @param array $attributes
   */
  public function __construct($content = null, array $attributes = [])
  {
    parent::__construct('i', $attributes, null);
    $this->setContent($content);
  }

}