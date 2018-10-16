<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

/**
 * Class TextAreaElement
 *
 * @package Subapp\WebApp\Web\Html\Element
 */
class TextAreaElement extends HtmlElement
{

  /**
   * TextAreaElement constructor.
   *
   * @param array $attributes
   * @param array $content
   */
  public function __construct(array $attributes, $content)
  {
    parent::__construct('textarea', $attributes, $content);
  }

}