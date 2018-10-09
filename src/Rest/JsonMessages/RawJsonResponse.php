<?php

namespace Subapp\WebApp\Rest\JsonMessages;

/**
 * Class RawJsonResponse
 * @package Subapp\WebApp\Rest\JsonMessages
 */
class RawJsonResponse extends ArrayResponse
{
    
    /**
     * RawJsonResponse constructor.
     * @param string $jsonString
     */
    public function __construct($jsonString)
    {
        parent::__construct(json_decode($jsonString, JSON_OBJECT_AS_ARRAY));
    }
    
}