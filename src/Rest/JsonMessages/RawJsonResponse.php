<?php

namespace Colibri\WebApp\Rest\JsonMessages;

/**
 * Class RawJsonResponse
 * @package Colibri\WebApp\Rest\JsonMessages
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