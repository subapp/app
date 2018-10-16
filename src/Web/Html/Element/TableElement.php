<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

class TableElement extends HtmlElement
{

  /**
   * TableElement constructor.
   *
   * @param null  $content
   * @param array $attributes
   */
  public function __construct($content = null, array $attributes = [])
  {
    parent::__construct('table', $attributes, null);
    $this->setContent($content);
  }

  /**
   * @param null $title
   * @return TableRowElement
   */
  public function row($title = null)
  {
    $tableRow = new TableRowElement();
    $this->appendContent($tableRow);

    if (!empty($title)) {
      $tableRow->cell($title);
    }

    return $tableRow;
  }

}
