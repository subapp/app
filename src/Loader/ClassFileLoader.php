<?php

namespace Subapp\WebApp\Loader;

use Subapp\Loader\LoaderInterface;

/**
 * Class ClassFileLoader
 * @package Subapp\WebApp\Loader
 */
class ClassFileLoader implements LoaderInterface
{
    
    /**
     * @inheritdoc
     */
    public function load($resource, $resourceType)
    {
        $classFile = new \SplFileObject($resource);
        
        return $this->findClassName($classFile);
    }
    
    /**
     * @inheritdoc
     */
    public function isSupported($resource, $resourceType)
    {
        return is_file($resource) && is_readable($resource) && 'php' === substr($resource, -3);
    }
    
    /**
     * @param \SplFileObject $classFile
     * @return string
     */
    protected function findClassName(\SplFileObject $classFile)
    {
        $namespace = null;
        $className = $classFile->getBasename('.php');
        
        if ($classFile->isFile()) {
            while (false === $classFile->eof()) {
                $line = $classFile->fgets();
                if (false !== strpos($line, 'namespace')) {
                    list(, $namespace) = explode("\x20", $line);
                    break;
                }
            }
        }
        
        return sprintf('%s\\%s', trim(trim($namespace), ";"), $className);
    }
    
}
