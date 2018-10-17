<?php

namespace Subapp\WebApp\Controller;

/**
 * Class Result
 * @package Subapp\WebApp\Controller
 */
class Result implements ResultInterface
{
    
    /**
     * @var string
     */
    protected $layout;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $pseudoPath;
    
    /**
     * @var string|null
     */
    protected $content = null;

    /**
     * @return boolean
     */
    public function hasLayout()
    {
        return ($this->layout !== null);
    }

    /**
     * @return boolean
     */
    public function hasTemplate()
    {
        return ($this->template !== null);
    }

    /**
     * @return boolean
     */
    public function hasContent()
    {
        return ($this->content !== null);
    }

    /**
     * @return boolean
     */
    public function hasPseudoPath()
    {
        return ($this->pseudoPath !== null);
    }

    /**
     * @return null|string
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * @param null|string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getPseudoPath()
    {
        return $this->pseudoPath;
    }

    /**
     * @param string $pseudoPath
     */
    public function setPseudoPath($pseudoPath)
    {
        $this->pseudoPath = $pseudoPath;
    }
    
}