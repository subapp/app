<?php

namespace Subapp\WebApp\Controller;

/**
 * Interface ResultInterface
 * @package Subapp\WebApp\Controller
 */
interface ResultInterface
{

    /**
     * @return boolean
     */
    public function hasLayout();

    /**
     * @return string
     */
    public function getLayout();

    /**
     * @param string $layout
     * @return string
     */
    public function setLayout($layout);

    /**
     * @return boolean
     */
    public function hasTemplate();

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * @param string $path
     * @return string
     */
    public function setTemplate($path);

    /**
     * @return boolean
     */
    public function hasContent();

    /**
     * @return null|string
     */
    public function getContent();
    
    /**
     * @param null|string $controllerContent
     */
    public function setContent($controllerContent);

    /**
     * @return boolean
     */
    public function hasPseudoPath();

    /**
     * @return string
     */
    public function getPseudoPath();

    /**
     * @param string $pseudoPath
     */
    public function setPseudoPath($pseudoPath);
    
}