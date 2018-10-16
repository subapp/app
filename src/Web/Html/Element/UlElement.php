<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

class UlElement extends HtmlElement
{

  /**
   * UlElement constructor.
   *
   * @param array $userList
   * @param array $attributes
   */
  public function __construct(array $userList = [], array $attributes = [])
  {
    parent::__construct('ul', $attributes, null);
    $this->setSingle(false)->setContent($userList);
  }

}