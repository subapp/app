<?php

namespace Colibri\WebApp\Response\JsonMessages;

/**
 * Class ExceptionResponse
 * @package Colibri\Webapp\Response\JsonMessages
 */
class ExceptionResponse extends ErrorResponse
{
    
    /**
     * @var string
     */
    public $stackTrace;
    
    /**
     * @inheritDoc
     */
    public function __construct(\Throwable $exception, $withStackTrace = false)
    {
        $reflection         = new \ReflectionObject($exception);
        $statusCode         = $exception->getCode() ?: HttpResponse::SERVER_INTERNAL_ERROR;
        $exceptionMessage   = sprintf('[%s]: %s', $reflection->getShortName(), $exception->getMessage());
        
        parent::__construct($statusCode, $exceptionMessage);
        
        if ($withStackTrace) {
            $this->stackTrace = $exception->getTraceAsString();
        }
    }
    
}
