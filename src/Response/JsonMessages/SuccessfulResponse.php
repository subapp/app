<?php

namespace Colibri\WebApp\Response\JsonMessages;

use Colibri\Http;

/**
 * Class SuccessfulResponse
 * @package Colibri\Webapp\Response\JsonMessages
 */
class SuccessfulResponse extends Response
{
    
    /**
     * @inheritDoc
     */
    public function __construct(array $success = null, $message = null, $code = Http\Response::SUCCESS_OK)
    {
        parent::__construct(['message' => $message] + $success, null, $code);
    }
    
}