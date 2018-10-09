<?php

namespace Subapp\WebApp\Rest\JsonMessages;

use Subapp\Http\Response as HttpResponse;

/**
 * Class ExceptionResponse
 * @package Subapp\Webapp\Response\JsonMessages
 */
class ExceptionResponse extends ErrorResponse
{
    
    /**
     * @var string
     */
    public $stackTrace;
    
    /**
     * @var string
     */
    public $exceptionClassName;
    
    /**
     * ExceptionResponse constructor.
     * @param \Throwable $exception
     * @param bool       $withClassName
     * @param bool       $withStackTrace
     */
    public function __construct(\Throwable $exception, $withClassName = false, $withStackTrace = false)
    {
        $reflection         = new \ReflectionObject($exception);
        $statusCode         = $exception->getCode() ?: HttpResponse::SERVER_INTERNAL_ERROR;

        parent::__construct($statusCode, $exception->getMessage());
        
        if ($withStackTrace) {
            $this->stackTrace = $exception->getTraceAsString();
        }
    
        if ($withClassName) {
            $this->exceptionClassName = $reflection->getShortName();
        }
    }
    
}
