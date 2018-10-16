<?php

namespace Subapp\WebApp\Exception;

/**
 * Class MethodNotFoundException
 * @package Subapp\WebApp\Exception
 */
class MethodNotFoundException extends \Exception
{

    /**
     * MethodNotFoundException constructor.
     * @param string $class
     * @param string $name
     */
    public function __construct($class, $name)
    {
        $message = $this->composeMessage($class, $name);

        parent::__construct($message, 404, null);
    }

    /**
     * @param string $class
     * @param string $name
     * @return string
     */
    private function composeMessage($class, $name)
    {
        $message = 'Method %s::%s(); does not exist in object';

        return sprintf($message, $class, $name);
    }
}