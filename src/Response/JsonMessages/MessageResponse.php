<?php

namespace Colibri\WebApp\Response\JsonMessages;

use Colibri\Http;

/**
 * Class MessageResponse
 * @package Colibri\Webapp\Response\JsonMessages
 */
class MessageResponse extends SuccessfulResponse
{
    
    /**
     * @inheritDoc
     */
    public function __construct($message = null, $code = Http\Response::SUCCESS_OK)
    {
        parent::__construct([], $message, $code);
    }
    
}