<?php

namespace Colibri\WebApp\Response\JsonMessages;

use Colibri\Http\Response as HttpResponse;

/**
 * Class Error
 * @package Colibri\WebApp\Response\JsonMessages
 */
class ErrorResponse extends AbstractResponse
{
    
    /**
     * @var int
     */
    public $code    = HttpResponse::SERVER_INTERNAL_ERROR;
    
    /**
     * @var null|array
     */
    public $error = null;
    
    /**
     * Response constructor.
     *
     * @param integer $code
     * @param string $error
     */
    public function __construct($code, $error)
    {
        $this->code = $code;
        $this->error = $error;
    }
    
}