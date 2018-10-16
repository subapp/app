<?php

    namespace Subapp\WebApp\Web\Html\Element;

    use Subapp\WebApp\Web\Html\HtmlElement;

    class TableBodyElement extends HtmlElement {

        /**
         * TableBodyElement constructor.
         * @param null $content
         * @param array $attributes
         */
        public function __construct($content = null, array $attributes = [])
        {
            parent::__construct('tbody', $attributes, null);
            $this->setContent($content);
        }

    }