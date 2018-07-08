<?php

namespace Colibri\WebApp\Response\JsonMessages;

/**
 * Class ErrorResponse
 * @package Colibri\Webapp\Response\JsonMessages
 */
class ErrorResponse extends Response
{
    /**
     * @inheritDoc
     */
    public function __construct($message = null, $code = 0)
    {
        parent::__construct(null, ['message' => $message, 'result' => null], $code);
    }
    
}