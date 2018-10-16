<?php

namespace Subapp\WebApp\Exception;

use Subapp\Router\Router;

/**
 * Class RouteNotFoundException
 * @package Subapp\WebApp\Exception
 */
class RouteNotFoundException extends NotFoundException
{

    /**
     * RouteNotFoundException constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $message = $this->composeMessage($router);

        parent::__construct($message, 404, null);
    }

    /**
     * @param Router $router
     * @return string
     */
    private function composeMessage(Router $router)
    {
        $request = $router->getRequest();
        $message = 'Route for request URI %s %s does not exist';

        return sprintf($message, $request->requestMethod(), $router->getTargetUri());
    }

}