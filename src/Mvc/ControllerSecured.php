<?php

namespace Subapp\WebApp\Mvc;

use Subapp\WebApp\Auth\AuthorizationCheckerTrait;

/**
 * Class ControllerSecured
 * @package Subapp\Webapp\WebApp
 */
class ControllerSecured extends AbstractController
{
    
    use AuthorizationCheckerTrait;
    
    /**
     * @inheritdoc
     */
    public function beforeExecute()
    {
        parent::beforeExecute();
        
        $this->authorizationCheck($this);
    }
    
}