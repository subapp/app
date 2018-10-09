<?php

namespace Subapp\WebApp\Template;

use Subapp\Template\Core\ExtensionInterface;
use Subapp\Template\Template;
use Subapp\UrlGenerator\UrlBuilder;
use Subapp\WebApp\ApplicationContainer;

/**
 * Class ExtensionCore
 * @package Subapp\Webapp\Template
 */
class ExtensionCore implements ExtensionInterface
{
    
    /**
     * @param Template $template
     * @throws \Subapp\Template\TemplateException
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