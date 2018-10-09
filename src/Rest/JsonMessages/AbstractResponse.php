<?php

namespace Subapp\WebApp\Rest\JsonMessages;

use Subapp\WebApp\Rest\JsonMessages\Helper\ModelNameAwareTrait;

/**
 * Class AbstractResponse
 * @package Subapp\Webapp\Response\JsonMessages
 */
abstract class AbstractResponse implements \JsonSerializable
{
    
    use ModelNameAwareTrait;
    
    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
    
    /**
     * @return \Generator
     */
    public function getPublicProperties()
    {
        $reflection = new \ReflectionObject($this);
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyValue = $property->getValue($this);
            
            if (null !== $propertyValue) {
                yield $property->getName() => $propertyValue;
            }
        }
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        return iterator_to_array($this->getPublicProperties());
    }
    
}