<?php

namespace Colibri\WebApp\Response;

use Colibri\Http;
use Colibri\Http\Response\Format;
use Colibri\WebApp\Response\JsonMessages;

/**
 * Class JsonResponseFormatter
 * @package Colibri\Webapp\Response
 */
class JsonResponseFormatter extends Format
{
    
    /**
     * @inheritdoc
     */
    public function process()
    {
        $this->response->setHeader('Content-type', 'application/json');
        $this->response->setContent(json_encode($this->createResponseBody()->toArray(), JSON_PRETTY_PRINT));
        
        return $this->response;
    }
    
    /**
     * @return JsonMessages\AbstractResponse
     * @throws \Colibri\Http\Exception
     */
    private function createResponseBody()
    {
        $response   = $this->response;
        $content    = $response->getContent();
        
        if ($content instanceof \Throwable) {
            $content = new JsonMessages\ExceptionResponse($content);
        }
        
        if (!($content instanceof JsonMessages\AbstractResponse)) {
            $content = new JsonMessages\ErrorResponse(Http\Response::SERVER_INTERNAL_ERROR, sprintf(
                'Unexpected response from controller method. Should be object which extends %s class "%s" passed',
                JsonMessages\AbstractResponse::class, gettype($content)));
        }
        
        $reflection = new \ReflectionObject($content);
        
        if ($reflection->hasProperty('code')) {
            $property           = $reflection->getProperty('code');
            $statusCode         = (integer)$property->getValue($content);
            $isDifferentCode    = ($response->getStatusCode() !== $statusCode);
            
            if ($isDifferentCode && $statusCode >= 100 && $statusCode <= 599) {
                $response->setStatusCode($statusCode);
            }
        }
        
        return $content;
    }
    
}
