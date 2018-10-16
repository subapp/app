<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

/**
 * Class H3Element
 *
 * @package Subapp\WebApp\Web\Html\Element
 */
class H3Element extends HtmlElement
{

  /**
   * H3Element constructor.
   *
   * @param null  $content
   * @param array $attributes
   */
  public function __construct($content = null, array $attributes = [])
  {
    parent::__construct('h3', $attributes, null);

    $this->setContent($content);
  }

}