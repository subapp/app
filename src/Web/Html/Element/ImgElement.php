<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

class ImgElement extends HtmlElement
{

  /**
   * ImgElement constructor.
   *
   * @param null  $src
   * @param array $attributes
   */
  public function __construct($src = null, array $attributes = [])
  {
    parent::__construct('img', $attributes, null);
    $this->setAttribute('src', $src)->isSingle(true);
  }

}