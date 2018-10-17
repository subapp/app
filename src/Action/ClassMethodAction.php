<?php

namespace Subapp\WebApp\Action;

/**
 * Class ClassMethodAction
 * @package Subapp\WebApp\Action
 */
class ClassMethodAction extends AbstractAction
{
    
    /**
     * @var string
     */
    private $namespace;
    
    /**
     * @var string
     */
    private $class;
    
    /**
     * @var string
     */
    private $method;

    /**
     * @var object
     */
    private $object;

    /**
     * @var bool
     */
    private $asStatic = false;

    /**
     * @var Instantiator
     */
    private $instantiator;
    
    /**
     * FallbackAction constructor.
     * @param string $namespace
     * @param string $class
     * @param string $method
     */
    public function __construct($namespace, $class, $method)
    {
        $this->namespace = $namespace;
        $this->class = $class;
        $this->method = $method;
        $this->instantiator = new Instantiator(null);
    }
    
    /**
     * @return callable|Callback
     */
    public function getCallback()
    {
        return $this->asStatic() ? $this->getStaticCallback() : $this->getObjectCallback();
    }

    /**
     * @return Callback
     */
    public function getStaticCallback()
    {
        return new Callback([$this->getClassName(), $this->getMethod()]);
    }

    /**
     * @return Callback
     */
    public function getObjectCallback()
    {
        return new Callback([$this->getObject(), $this->getMethod()]);
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
    
    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
    
    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
    
    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }
    
    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
    
    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return boolean
     */
    public function asStatic()
    {
        return $this->asStatic;
    }

    /**
     * @param boolean $asStatic
     */
    public function setAsStatic($asStatic)
    {
        $this->asStatic = $asStatic;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return trim($this->getNamespace(), '\\') . '\\' . $this->getClass();
    }

    /**
     * @return Instantiator
     */
    public function getInstantiator()
    {
        return $this->instantiator;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        $isNotInstantiated = ($this->object === null);

        if ($isNotInstantiated) {
            $this->object = $this->getInstantiator()->getInstance($this->getClassName());
        }

        return $this->object;
    }

}