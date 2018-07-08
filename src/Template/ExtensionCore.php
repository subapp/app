<?php

namespace Colibri\WebApp\Template;

use Colibri\Template\Core\ExtensionInterface;
use Colibri\Template\Template;
use Colibri\UrlGenerator\UrlBuilder;
use Colibri\WebApp\ApplicationContainer;

/**
 * Class ExtensionCore
 * @package Colibri\Webapp\Template
 */
class ExtensionCore implements ExtensionInterface
{
    
    /**
     * @param Template $template
     * @throws \Colibri\Template\TemplateException
     */
    public function register(Template $template)
    {
        $template->registerFunction('url', [$this, 'createURL']);
    }
    
    /**
     * @param       $macros
     * @param array $params
     * @param array $query
     * @return null|string
     */
    public function createURL($macros, array $params = [], array $query = [])
    {
        /** @var UrlBuilder $url */
        $url = ApplicationContainer::instance()->get('url');
        
        if (false === strpos($macros, ':')) {
            return $url->path($macros, $query);
        } else {
            return $url->create($macros, $params, $query);
        }
    }
    
}