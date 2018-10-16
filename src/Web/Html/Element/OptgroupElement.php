<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

class OptgroupElement extends HtmlElement
{

  /**
   * OptgroupElement constructor.
   *
   * @param null  $content
   * @param array $attributes
   */
  public function __construct($content = null, array $attributes = [])
  {
    parent::__construct('optgroup', $attributes, null);
    $this->setContent($content);
  }

}
