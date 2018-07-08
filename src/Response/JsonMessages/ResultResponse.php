<?php

namespace Colibri\WebApp\Response\JsonMessages;

use Colibri\Http;

/**
 * Class ResultResponse
 * @package Colibri\Webapp\Response\JsonMessages
 */
class ResultResponse extends SuccessfulResponse
{
    
    /**
     * @inheritDoc
     */
    public function __construct($result = null, $message = null, $code = Http\Response::SUCCESS_OK)
    {
        parent::__construct(['result' => $result], $message, $code);
    }
    
}