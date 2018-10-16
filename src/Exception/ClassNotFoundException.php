<?php

namespace Subapp\WebApp\Exception;

/**
 * Class ClassNotFoundException
 * @package Subapp\WebApp\Exception
 */
class ClassNotFoundException extends NotFoundException
{

    /**
     * ClassNotFoundException constructor.
     * @param string $className
     */
    public function __construct($className)
    {
        $message = $this->composeMessage($className);

        parent::__construct($message, 404, null);
    }

    /**
     * @param string $className
     * @return string
     */
    private function composeMessage($className)
    {
        $message = 'Class %s couldn\'t be found';

        return sprintf($message, $className);
    }

}