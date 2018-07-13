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
        $reflection = new \ReflectionObject($exception);
        
        parent::__construct($exception->getCode(), sprintf('[%s]: %s', $reflection->getShortName(), $exception->getMessage()));
        
        if ($withStackTrace) {
            $this->stackTrace = $exception->getTraceAsString();
        }
    }
    
}