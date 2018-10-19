<?php

namespace Subapp\WebApp\Controller\Executor;

use Subapp\WebApp\Controller\Result;

/**
 * Class FunctionExecutor
 * @package Subapp\WebApp\Controller\Executor
 */
class FunctionExecutor extends AbstractExecutor
{
    
    /**
     * @return \Subapp\WebApp\Controller\ResultInterface
     */
    public function execute()
    {
        $result = new Result();
        
        $result->setContent($this->getAction()->executeCallback());
        $result->setTemplate('Function/Result');
        $result->setPseudoPath('System');
        
        return $result;
    }
    
}