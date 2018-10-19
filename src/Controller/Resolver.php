<?php

namespace Subapp\WebApp\Controller;

use Subapp\Http\Response;
use Subapp\Template\NullTemplate;
use Subapp\Template\TemplateInterface;
use Subapp\WebApp\Action\ActionInterface;
use Subapp\WebApp\Controller\Executor\ExecutorInterface;

/**
 * Class ActionExecutor
 * @package Subapp\WebApp\Controller
 */
class Resolver
{
    
    /**
     * @var Response
     */
    private $response;
    
    /**
     * @var TemplateInterface
     */
    private $compiler;
    
    /**
     * @var array|callable
     */
    private $fallback;
    
    /**
     * @var ExecutorFactory
     */
    private $factory;
    
    /**
     * ActionExecutor constructor.
     * @param Response          $response
     * @param TemplateInterface $template
     */
    public function __construct(Response $response, TemplateInterface $template)
    {
        $this->factory = new ExecutorFactory();
        $this->compiler = $template;
        $this->response = $response;
    }
    
    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
    
    /**
     * @param ActionInterface $action
     * @return ExecutorInterface
     */
    public function createExecutor(ActionInterface $action)
    {
        return $this->factory->getExecutor($action);
    }
    
    /**
     * @return TemplateInterface
     */
    public function getCompiler()
    {
        return $this->compiler ?? new NullTemplate();
    }
    
    /**
     * @param TemplateInterface $compiler
     */
    public function setCompiler(TemplateInterface $compiler)
    {
        $this->compiler = $compiler;
    }
    
    /**
     * @return array|callable
     */
    public function getFallback()
    {
        return $this->fallback;
    }
    
    /**
     * @param array|callable $fallback
     */
    public function setFallback($fallback)
    {
        $this->fallback = $fallback;
    }
    
    /**
     * @param ActionInterface $action
     * @return void
     */
    public function execute(ActionInterface $action)
    {
        $this->getResponse()->setContent($this->process($action));
    }
    
    /**
     * @param ExecutorInterface $executor
     * @return null|ResultInterface
     */
    private function executeResolver(ExecutorInterface $executor)
    {
        $result = null;
        
        try {
            $result = $executor->execute();
        } catch (\Throwable $exception) {
            $result = $this->executeFallback($exception);
        }
        
        return $result;
    }
    
    /**
     * @param \Throwable $exception
     * @return null|ResultInterface
     * @throws \Throwable
     */
    private function executeFallback(\Throwable $exception)
    {
        $result = null;
        $arguments = [$this->getResponse(), $exception,];

        try {
            $action = (new ActionFactory())->getAction($this->getFallback());
            $action->setArguments(...$arguments);
            $result = $this->factory->getExecutor($action)->execute();
        } catch (\Throwable $e) {
            throw $exception;
        }

        return $result;
    }
    
    /**
     * @param ActionInterface $action
     * @return null|string
     */
    public function process(ActionInterface $action)
    {
        $executor = $this->createExecutor($action);
        $response = $this->getResponse();
        
        $result = $this->executeResolver($executor);

        $content = null;
        
        // If content body is disabled for response, nothing to set in it
        // Used in case with redirect, for example
        if ($response->isEnableBody() === true && $result instanceof ResultInterface) {
            
            $content = $result->getContent();
            
            // Special condition for handle controller templates
            if ($response->getBodyFormat() == Response::RESPONSE_HTML) {
                
                // If controller action nothing return, we try render inner template
                if (!$result->hasContent() && $result->hasTemplate()) {
                    $content = $this->render($result->getTemplate(), $result->getPseudoPath());
                }
                
                // If controller has main layout trying, we try wrap in it
                if ($result->hasLayout()) {
                    $content = $this->render($result->getLayout(), null, ['content' => $content]);
                }
            }
        }
        
        return $content;
    }
    
    /**
     * @param string $path
     * @param string $pseudoPath
     * @param array  $data
     * @return string
     */
    protected function render($path, $pseudoPath, array $data = [])
    {
        $compiler = $this->getCompiler();
        $template = (null === $pseudoPath) ? $path : "$pseudoPath::$path";
        $content = $compiler->fetch($template, $data);
        
        return $content;
    }
    
}