<?php

namespace Colibri\WebApp\Loader;

use Colibri\Annotations\Reader;
use Colibri\ServiceLocator\ContainerInterface;
use Colibri\WebApp\Routing\Route;

/**
 * Class AnnotationDirectoryLoader
 * @package Colibri\WebApp\Loader
 */
class AnnotationDirectoryLoader implements LoaderInterface
{
  
  /**
   * @var ContainerInterface
   */
  protected $serviceLocator;
  
  /**
   * AnnotationDirectoryLoader constructor.
   * @param ContainerInterface $serviceLocator
   */
  public function __construct(ContainerInterface $serviceLocator)
  {
    $this->serviceLocator = $serviceLocator;
  }
  
  /**
   * @param $filepath
   */
  public function load($filepath)
  {
    $iterator = new \RecursiveDirectoryIterator($filepath,
      \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::FOLLOW_SYMLINKS);
    
    /** @var \SplFileInfo $fileinfo */
    foreach ($iterator as $fileinfo) {
      
      $className = $this->findClassName($fileinfo->getRealPath());
      $reflection = new \ReflectionClass($className);
      $reader = new Reader();
      $parser = $reader->getParser();
      
      $parser->addNamespace('Colibri\\WebApp\\Routing');
      
      foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
        if (false !== strpos($method->getName(), 'Action') && $method->getDeclaringClass()->getName() === $className) {
          foreach ($reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof Route) {
              if (empty($annotation->matches)) {
                $annotation->matches = [
                  'controller' => strtolower(str_replace('Controller', null, $fileinfo->getBasename('.php'))),
                  'action' => strtolower(str_replace('Action', null, $method->getName())),
                ];
              }
              $annotation->register($this->serviceLocator->get('router'));
            }
          }
        }
      }
      
      foreach ($reflection->getProperties() as $property) {
        $reader->getPropertyAnnotations($property);
      }
      
    }
  }
  
  /**
   * @param $classFile
   * @return string
   */
  protected function findClassName($classFile)
  {
    $tokens = token_get_all(file_get_contents($classFile));
  
    $namespace = null;
    $className = null;
    
    for ($i = 0; isset($tokens[$i]); $i++) {
      $token = $tokens[$i];
      
      if (T_NAMESPACE === $token[0]) {
        while ($tokens[$i++][0] === T_PAAMAYIM_NEKUDOTAYIM);
        while (isset($tokens[++$i]) && in_array($tokens[$i][0], [T_NS_SEPARATOR, T_STRING])) {
          $namespace .= $tokens[$i][1];
          continue(1);
        }
      }
  
      if (T_CLASS === $token[0]) {
        while ($tokens[++$i][0] !== T_STRING);
        if ($tokens[$i][0] === T_STRING) {
          $className = $tokens[$i][1];
          break;
        }
      }
    }
    
    return sprintf('%s\\%s', $namespace, $className);
  }
  
}