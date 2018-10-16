<?php

namespace Subapp\WebApp\Loader;

use Subapp\Collection\Collection;

/**
 * Class DirectoryClassFileLoader
 * @package Subapp\WebApp\Loader
 */
class DirectoryClassFileLoader extends ClassFileLoader
{
    
    /**
     * @param $resource
     * @param $resourceType
     * @return Collection
     */
    public function load($resource, $resourceType)
    {
        $collection = new Collection();
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
