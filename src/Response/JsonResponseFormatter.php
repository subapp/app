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
     * @return JsonMessages\Response
     * @throws \Colibri\Http\Exception
     */
    private function createResponseBody()
    {
        $response = $this->response->getContent();
        
        if ($response instanceof \Throwable) {
            $response = new JsonMessages\ExceptionResponse($response);
            $response->setStatusCode(Http\Response::SERVER_INTERNAL_ERROR);
        }
        
        if (!($response instanceof JsonMessages\Response)) {
            $response = new JsonMessages\ErrorResponse(sprintf(
                'Unexpected response from controller method. Should be object which extends %s class "%s" passed',
                JsonMessages\Response::class, gettype($response)));
            $response->setStatusCode(Http\Response::SERVER_INTERNAL_ERROR);
        }
        
        if (false === (boolean)$response->getStatusCode()) {
            $response->setStatusCode($this->response->getStatusCode());
        } else {
            $this->response->setStatusCode($response->getStatusCode());
        }
        
        $response->setStatusMessage($this->response->getStatusMessage($response->getStatusCode()));
        
        return $response;
    }
    
}