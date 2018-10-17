<?php

namespace Subapp\WebApp\Controller;

use Subapp\Http\Response;
use Subapp\Template\NullTemplate;
use Subapp\Template\TemplateInterface;
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
     * @var ExecutorInterface
     */
    private $executor;
    
    /**
     * @var TemplateInterface
     */
    private $compiler;

    /**
     * @var array|callable
     */
    private $fallback;
    
    /**
     * ActionExecutor constructor.
     * @param Response $response
     * @param ExecutorInterface $executor
     */
    public function __construct(Response $response, ExecutorInterface $executor)
    {
        $this->response = $response;
        $this->executor = $executor;
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
     * @return ExecutorInterface
     */
    public function getExecutor()
    {
        return $this->executor;
    }
    
    /**
     * @param ExecutorInterface $executor
     */
    public function setExecutor(ExecutorInterface $executor)
    {
        $this->executor = $executor;
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
     * @return void
     */
    public function execute()
    {
        $this->getResponse()->setContent($this->process());
    }

    /**
     * @param ExecutorInterface $executor
     * @return null|Result
     */
    private function executeResolver(ExecutorInterface $executor)
    {
        $result = null;

        try {
            $result = $executor->execute();
        } catch (\Throwable $exception) {
            die(var_dump(__METHOD__, $exception->getMessage()));
            $result = $this->executeResolver();
        }

        return $result;
    }

    /**
     * @return null|string
     */
    public function process()
    {
        $executor = $this->getExecutor();
        $response = $this->getResponse();

        $result = $this->executeResolver($executor);

        $content = null;

        // If content body is disabled for response, nothing to set in it
        // Used in case with redirect, for example
        if ($response->isEnableBody() === true) {

            // Special condition for handle controller templates
            if ($response->getBodyFormat() == Response::RESPONSE_HTML) {

                // If controller action nothing return, we try render inner template
                $content = $result->hasContent()
                    ? $result->getContent() : $this->render($result->getTemplate(), $result->getPseudoPath());

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