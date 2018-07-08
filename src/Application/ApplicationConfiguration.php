<?php

namespace Colibri\WebApp\Application;

use Colibri\Collection\ProxyInterface;
use Colibri\Parameters\ParametersCollection;
use Colibri\WebApp\Exception\RuntimeWebAppException;

/**
 * Class ApplicationConfiguration
 * @package Colibri\WebApp\Application
 */
class ApplicationConfiguration extends ParametersCollection implements ProxyInterface
{
    
    const KEY_SERVER = 'server';
    const KEY_APPLICATION = 'application';
    
    // server keys
    const KEY_TIMEZONE = 'timezone';
    const KEY_DISPLAY_ERRORS = 'displayErrors';
    const KEY_ERROR_LEVEL = 'errorLevel';
    
    //application keys
    const KEY_CONTROLLER_NS = 'controller.defaultNamespace';
    
    const KEY_URI_BASE = 'uri.base';
    const KEY_URI_STATIC = 'uri.static';
    
    const KEY_TEMPLATE_DIRECTORY = 'template.root';
    
    /**
     * @var bool
     */
    private $initialized = false;
    
    /**
     * ApplicationConfiguration constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
    }
    
    /**
     * @return string
     */
    public function getControllerNS()
    {
        return $this->getApplicationConfiguration()->getValue(static::KEY_CONTROLLER_NS);
    }
    
    /**
     * @return string
     */
    public function getURIBasePath()
    {
        return $this->getApplicationConfiguration()->getValue(static::KEY_URI_BASE);
    }
    
    /**
     * @return string
     */
    public function getURIStaticPath()
    {
        return $this->getApplicationConfiguration()->getValue(static::KEY_URI_STATIC);
    }
    
    /**
     * @return string
     */
    public function getTemplateDirectory()
    {
        return $this->getApplicationConfiguration()->getValue(static::KEY_TEMPLATE_DIRECTORY, false);
    }
    
    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->getServerConfiguration()->getValue(static::KEY_TIMEZONE);
    }
    
    /**
     * @return string
     */
    public function getDisplayErrors()
    {
        return $this->getServerConfiguration()->getValue(static::KEY_DISPLAY_ERRORS);
    }
    
    /**
     * @return string
     */
    public function getErrorLevel()
    {
        return $this->getServerConfiguration()->getValue(static::KEY_ERROR_LEVEL);
    }
    
    /**
     * @param      $key
     * @param bool $required
     * @return ParametersCollection|mixed|null
     * @throws RuntimeWebAppException
     */
    public function getValue($key, $required = true)
    {
        $value = $this->offsetGet($key);
        
        if (null === $value && $required) {
            throw new RuntimeWebAppException(sprintf('Configuration key is required "%s"', $key));
        }
        
        return $value;
    }
    
    /**
     * @return ApplicationConfiguration
     */
    public function getApplicationConfiguration()
    {
        return $this->get(static::KEY_APPLICATION);
    }
    
    /**
     * @return ApplicationConfiguration
     */
    public function getServerConfiguration()
    {
        return $this->get(static::KEY_SERVER);
    }
    
    /**
     * @inheritdoc
     */
    public function path($offset, $separator = self::PATH_SEPARATOR)
    {
        $separator = $separator ?? self::PATH_SEPARATOR;
        
        return parent::path($offset, $separator);
    }
    
    /**
     * @return void
     * @throws RuntimeWebAppException
     */
    public function initialize()
    {
        if (!$this->isInitialized()) {
            
            $keys = [static::KEY_SERVER, static::KEY_APPLICATION,];
            
            foreach ($keys as $key) {
                if (!$this->offsetExists($key)) {
                    throw new RuntimeWebAppException(sprintf('Configuration dont have a "%s" block', $key));
                }
            }
            
            $this->initialized = true;
        }
    }
    
    /**
     * @return bool
     */
    public function isInitialized()
    {
        return $this->initialized;
    }
    
}