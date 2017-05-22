<?php

namespace App\Controller;

use Colibri\WebApp\Controller;
use Colibri\WebApp\Routing\Route;

class IndexController extends Controller
{
  
  public function __construct()
  {
//        $this->setLayout('layout');
  }
  
  /**
   * @Route(pattern="/test/123")
   * @param int $id
   */
  public function indexAction($id = 0)
  {
    
  }
  
  /**
   * @Route(pattern="/qwerty123")
   */
  public function test1Action()
  {
    $this->view->set('content', __METHOD__);
    $this->view->set('sub_content', $this->execute([
      'namespace' => '\\App\Controller',
      'action' => 'test2',
      'params' => [__METHOD__]
    ]));
  }
  
  /**
   * @Route(pattern="/do/:id")
   */
  public function test2Action($id)
  {
    return json_encode([__METHOD__, $id]);
  }
  
  public function helloAction()
  {
    $this->view->set('controller', $this->execute([
      'action' => 'test2'
    ]));
    $this->response->setContent([1]);
  }
  
}