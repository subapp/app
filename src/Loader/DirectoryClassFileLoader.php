<?php

namespace Colibri\WebApp\Loader;

use Colibri\Collection\ArrayCollection;

/**
 * Class DirectoryClassFileLoader
 * @package Colibri\WebApp\Loader
 */
class DirectoryClassFileLoader extends ClassFileLoader
{
    
    /**
     * @param $resource
     * @param $resourceType
     * @return ArrayCollection
     */
    public function load($resource, $resourceType)
    {
        $collection = new ArrayCollection();
        $iterator = new \RecursiveDirectoryIterator($resource,
            \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS);
        $iterator = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
        
        /** @var \SplFileInfo $classFile */
        foreach ($iterator as $classFile) {
            if ($classFile->isFile() && parent::isSupported($classFile->getPathname(), null)) {
                $classFile = new \SplFileObject($classFile->getRealPath());
                if ($className = $this->findClassName($classFile)) {
                    $collection->append($className);
                }
            }
        }
        
        return $collection;
    }
    
    /**
     * @inheritdoc
     */
    public function isSupported($resource, $resourceType)
    {
        return is_dir($resource);
    }
    
}
