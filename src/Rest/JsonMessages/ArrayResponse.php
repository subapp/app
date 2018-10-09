<?php

namespace Subapp\WebApp\Rest\JsonMessages;

/**
 * Class FlatArrayResponse
 * @package Subapp\WebApp\Rest\JsonMessages
 */
class ArrayResponse extends AbstractResponse
{
    
    /**
     * FlatArrayResponse constructor.
     * @param array $content
     */
    public function __construct(array $content = [])
    {
        foreach ($content as $name => $value) {
            $this->{$name} = $value;
        }
    }
    
}