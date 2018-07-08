<?php

namespace Colibri\WebApp\Mvc;

use Colibri\WebApp\Auth\AuthorizationCheckerTrait;

/**
 * Class ControllerSecured
 * @package Colibri\Webapp\WebApp
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