<?php

namespace Subapp\WebApp\Action;

use Subapp\WebApp\Exception\ClassNotFoundException;
use Subapp\WebApp\Exception\MethodNotFoundException;
use Subapp\WebApp\Exception\NotFoundException;

/**
 * Class Instantiator
 * @package Subapp\WebApp\Action
 */
class Instantiator
{

    /**
     * @var string
     */
    private $expectedInterface;

    /**
     * Instantiator constructor.
     * @param string $expectedInterface
     */
    public function __construct($expectedInterface = null)
    {
        $this->expectedInterface = $expectedInterface;
    }

    /**
     * @param string $class
     * @return object
     */
    public function getInstance($class)
    {
        $reflection = $this->getClassReflection($class);
        
        return $reflection->newInstance();
    }

    /**
     * @param $class
     * @return \ReflectionClass
     * @throws ClassNotFoundException|\Throwable
     */
    public function getClassReflection($class)
    {
        try {
            $reflection = is_object($class) ? new \ReflectionObject($class) : new \ReflectionClass($class);
            if ($this->hasExpectedInterface() && !$reflection->implementsInterface($this->getExpectedInterface())) {
                throw new NotFoundException(sprintf(
                    'Irregular class name it must implement interface %s',
                    $this->getExpectedInterface()));
            }
        } catch (\ReflectionException $exception) {
            throw new ClassNotFoundException($class);
        } catch (\Throwable $exception) {
            throw $exception;
        }

        return $reflection;
    }

    /**
     * @param object|string $class
     * @param $name
     * @return \ReflectionMethod
     * @throws MethodNotFoundException
     */
    public function getMethodReflection($class, $name)
    {
        $reflection = $this->getClassReflection($class);

        try {
            $method = $reflection->getMethod($name);
        } catch (\ReflectionException $exception) {
            throw new MethodNotFoundException($reflection->getName(), $name);
        }

        return $method;
    }

    /**
     * @return string
     */
    public function getExpectedInterface()
    {
        return $this->expectedInterface;
    }

    /**
     * @param string $expectedInterface
     */
    public function setExpectedInterface($expectedInterface)
    {
        $this->expectedInterface = $expectedInterface;
    }

    /**
     * @return boolean
     */
    public function hasExpectedInterface()
    {
        return $this->expectedInterface
            && (interface_exists($this->expectedInterface) || class_exists($this->expectedInterface));
    }

}