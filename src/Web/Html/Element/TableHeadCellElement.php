<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

class TableHeadCellElement extends HtmlElement
{

  /**
   * TableHeadCellElement constructor.
   *
   * @param null  $content
   * @param array $attributes
   */
  public function __construct($content = null, array $attributes = [])
  {
    parent::__construct('th', $attributes, null);
    $this->setContent($content);
  }

}