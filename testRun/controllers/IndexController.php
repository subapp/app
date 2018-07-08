<?php

namespace App\Controller;

use Colibri\WebApp\Controller;
use Colibri\WebApp\Annotation\Route;

class IndexController extends Controller
{
    
    protected $test;
    
    public function __construct()
    {
    
    }
    
    /**
     * @Route(pattern="/test/123", method=@Method(methods='POST'))
     * @param int $id
     */
    public function indexAction($id = 0)
    {
        die(__METHOD__);
        $this->setLayout('layout');
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