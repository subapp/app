<?php

namespace Subapp\WebApp\Rest\JsonMessages\Helper;

/**
 * Trait ModelNameAwareTrait
 * @package Subapp\WebApp\Rest\JsonMessages\Helper
 */
trait ModelNameAwareTrait
{
    
    /**
     * @var string
     */
    public $entity;
    
    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entity;
    }
    
    /**
     * @param string $modelName
     */
    public function setEntityName($modelName)
    {
        $this->entity = $modelName;
    }
    
    /**
     * @return void
     */
    public function useClassName()
    {
        $this->setEntityName($this->normalizeClassName(static::class));
    }
    
    /**
     * @param $className
     * @return string
     */
    private function normalizeClassName($className)
    {
        list($name, $prefix) = array_reverse(explode('\\', $className));
        
        return sprintf('%s.%s', lcfirst($prefix), lcfirst($name));
    }
    
}