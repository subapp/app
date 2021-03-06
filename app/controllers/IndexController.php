<?php

namespace App\Controller;

use Subapp\Http\Response;
use Subapp\WebApp\Annotation\Route;
use Subapp\WebApp\Mvc\AbstractController;
use Subapp\WebApp\Rest\JsonMessages\ExceptionResponse;
use Subapp\WebApp\Rest\JsonResponseFormatter;

class IndexController extends AbstractController
{
    
    protected $test;
    
    public function __construct()
    {
    
    }
    
    public function fallbackAction(Response $response, \Throwable $exception)
    {
        $response->setBodyFormat(Response::RESPONSE_CUSTOM, JsonResponseFormatter::class);
        
        $exception = new \Exception(sprintf('Application halt with exception: [%s] %s', get_class($exception), $exception->getMessage()));
        
        $config = $this->getConfig();
        
        $message = new ExceptionResponse($exception, true, $config->getDisplayErrors());
        $message->useClassName();
    
        return $message;
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
    
    public function testAction($id = 0)
    {
        return json_encode([__METHOD__, time(), $id]);
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