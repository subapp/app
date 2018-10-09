<?php

namespace Subapp\WebApp\Rest;

use Subapp\Http;
use Subapp\Http\Response\Format;
use Subapp\WebApp\Rest\JsonMessages;

/**
 * Class JsonResponseFormatter
 * @package Subapp\Webapp\Response
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
            $isAllowedCode      = ($statusCode >= 100 && $statusCode <= 599);
            
            if ($isDifferentCode && $isAllowedCode) {
                $response->setStatusCode($statusCode);
            } elseif ($isAllowedCode === false) {
                $property->setValue($content, $response->getStatusCode());
            }
        }
        
        return $content;
    }
    
}
