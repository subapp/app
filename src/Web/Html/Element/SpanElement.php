<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

class SpanElement extends HtmlElement
{

  public function __construct($content = null, array $attributes = [])
  {
    parent::__construct('span', $attributes, null);
    $this->setSingle(false)->setContent($content);
  }

}