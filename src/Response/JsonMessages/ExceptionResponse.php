<?php

namespace Colibri\WebApp\Response\JsonMessages;

/**
 * Class ExceptionResponse
 * @package Colibri\Webapp\Response\JsonMessages
 */
class ExceptionResponse extends ErrorResponse
{
    
    /**
     * @inheritDoc
     */
    public function __construct(\Throwable $exception)
    {
        $reflection = new \ReflectionObject($exception);
        
        parent::__construct(sprintf('[%s]: %s', $reflection->getShortName(), $exception->getMessage()), $exception->getCode());
    }
    
}