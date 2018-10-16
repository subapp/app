<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

/**
 * Class TableCellElement
 *
 * @package Subapp\WebApp\Web\Html\Element
 */
class TableCellElement extends HtmlElement
{

  /**
   * TableCellElement constructor.
   *
   * @param null  $content
   * @param array $attributes
   */
  public function __construct($content = null, array $attributes = [])
  {
    parent::__construct('td', $attributes, null);

    $this->setContent($content);
  }

}
