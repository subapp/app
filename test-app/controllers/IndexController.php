<?php

namespace App\Controller;

use Colibri\WebApp\Controller;

class IndexController extends Controller
{

    public function __construct()
    {
//        $this->setLayout('layout');
    }

    public function indexAction($id = 0)
    {

    }

    public function test1Action()
    {
        $this->view->set('content', __METHOD__);
        $this->view->set('sub_content', $this->execute([
            'namespace' => '\\App\Controller',
            'action' => 'test2',
            'params' => [__METHOD__]
        ]));
    }

    public function test2Action()
    {
        return __METHOD__;
    }

    public function helloAction()
    {
        $this->view->set('controller', $this->execute([
            'action' => 'test2'
        ]));
        $this->response->setContent([1]);
    }

}