<?php

namespace Colibri\WebApp\Response\JsonMessages;

/**
 * Class Response
 * @package Colibri\Webapp\Response\JsonMessages
 */
abstract class Response
{
    
    /**
     * @var array
     */
    protected $error;
    
    /**
     * @var array
     */
    protected $response;
    
    /**
     * @var integer
     */
    protected $statusCode;
    
    /**
     * @var string
     */
    protected $statusMessage;
    
    /**
     * @inheritDoc
     */
    public function __construct($response, $error, $statusCode)
    {
        $this->response = $response;
        $this->error = $error;
        $this->statusCode = $statusCode;
    }
    
    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }
    
    /**
     * @param array $error
     * @return $this
     */
    public function setError(array $error)
    {
        $this->error = $error;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * @param array $response
     * @return $this
     */
    public function setResponse(array $response)
    {
        $this->response = $response;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    
    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
    }
    
    /**
     * @param string $statusMessage
     * @return $this
     */
    public function setStatusMessage($statusMessage)
    {
        $this->statusMessage = $statusMessage;
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'isSuccess' => !$this->isError(),
            'response' => $this->response,
            'error' => $this->error,
            'status' => [
                'code' => $this->statusCode,
                'message' => $this->statusMessage,
            ],
        ];
    }
    
    /**
     * @return boolean
     */
    public function isError()
    {
        return null !== $this->error;
    }
    
}