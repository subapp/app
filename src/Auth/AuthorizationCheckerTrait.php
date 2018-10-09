<?php

namespace Subapp\WebApp\Auth;

use Subapp\Http\Response;
use Subapp\WebApp\Mvc\ControllerSecured;

/**
 * Class AuthorizationCheckerTrait
 * @package Subapp\Webapp\Auth
 */
trait AuthorizationCheckerTrait
{
    
    /**
     * @param ControllerSecured $controller
     */
    public function authorizationCheck(ControllerSecured $controller)
    {
        if (false === $controller->getAuth()->isAuthorized()) {
            $this->renderException(new \Exception('Unauthenticated access', 401));
        }
    }
    
    /**
     * @param \Throwable $exception
     */
    private function renderException(\Throwable $exception)
    {
        ob_clean();
        $response = new Response();
        $response->setStatusCode(500);
        $response->setBodyFormat(Response::RESPONSE_RAW);
        $response->setContentTypePlain();
        $response->setContent($exception->getMessage());
        $response->send();
        die;
    }
    
}