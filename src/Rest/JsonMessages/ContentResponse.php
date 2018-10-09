<?php

namespace Subapp\WebApp\Rest\JsonMessages;

use Subapp\Http\Response as HttpResponse;

/**
 * Class Response
 * @package Subapp\WebApp\Response\JsonMessages
 */
class ContentResponse extends AbstractResponse
{
    
    /**
     * @var int
     */
    public $code    = HttpResponse::SUCCESS_OK;
    
    /**
     * @var null|array
     */
    public $content = null;
    
    /**
     * Response constructor.
     *
     * @param $code
     * @param $content
     */
    public function __construct($code, $content)
    {
        $this->code = $code;
        $this->content = $content;
    }
    
}