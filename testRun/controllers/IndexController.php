<?php

namespace App\Controller;

use Subapp\Http\Response;
use Subapp\WebApp\Controller;
use Subapp\WebApp\Annotation\Route;
use Subapp\WebApp\Rest\JsonMessages\ExceptionResponse;
use Subapp\WebApp\Rest\JsonResponseFormatter;

class IndexController extends Controller
{
    
    protected $test;
    
    public function __construct()
    {
    
    }
    
    /**
     * @Route(pattern="/", method=@Method(methods='POST'))
     * @param int $id
     *
     * @return ExceptionResponse
     */
    public function indexAction($id = 0)
    {
        $this->response->setBodyFormat(Response::RESPONSE_CUSTOM, JsonResponseFormatter::class);
        
        $response = new ExceptionResponse(new \RuntimeException(__METHOD__, 500), true);
    
        $response->useClassName();
        
        return $response;
    }
    
    /**
     * @Route(pattern="/qwerty123")
     */
    public function test1Action()
    {
        $this->view->set('content', __METHOD__);
        $this->view->set('sub_content', $this->execute([
            'action' => 'test2Action',
            'params' => [777],
        ]));
    }
    
    /**
     * @Route(pattern="/do/:id")
     * @param int $id
     * @return string
     */
    public function test2Action($id = 0)
    {
        return json_encode([__METHOD__, $id]);
    }
    
    /**
     * @Route(pattern="/site/banner_40911", action="hello")
     */
    public function helloAction()
    {
        $this->view->set('controller', $this->execute([
            'action' => 'test2Action',
            'params' => [__LINE__],
        ]));
        $this->response->setContent([1]);
    }
    
}