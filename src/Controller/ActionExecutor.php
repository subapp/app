<?php

namespace Subapp\WebApp\Controller;

use Subapp\Http\Response;
use Subapp\Template\NullTemplate;
use Subapp\Template\TemplateInterface;

/**
 * Class ActionExecutor
 * @package Subapp\WebApp\Controller
 */
class ActionExecutor
{
    
    /**
     * @var Response
     */
    private $response;
    
    /**
     * @var ControllerResolver
     */
    private $resolver;
    
    /**
     * @var TemplateInterface
     */
    private $compiler;
    
    /**
     * ActionExecutor constructor.
     * @param $response
     * @param $resolver
     */
    public function __construct(Response $response, ControllerResolver $resolver)
    {
        $this->response = $response;
        $this->resolver = $resolver;
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
     * @return ControllerResolver
     */
    public function getResolver()
    {
        return $this->resolver;
    }
    
    /**
     * @param ControllerResolver $resolver
     */
    public function setResolver(ControllerResolver $resolver)
    {
        $this->resolver = $resolver;
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
     * @return null|string
     */
    public function process()
    {
        $resolver = $this->getResolver();
        $response = $this->getResponse();
        
        $result = $resolver->execute();
        $content = null;
        
        // If content body is disabled for response, nothing to set in it
        // Used in case with redirect, for example
        if ($response->isEnableBody() === true) {
            
            $content = $result->getControllerContent();
            $controller = $result->getControllerInstance();
            
            // Special condition for handle controller templates
            if ($response->getBodyFormat() == Response::RESPONSE_HTML) {
                // If controller action nothing return, we try render inner template
                // controller_name/action_name.php
                if (null === $content) {
                    $templatePath = "{$resolver->getControllerCamelize()}/{$resolver->getActionCamelize()}";
                    $content = $this->render($templatePath, $controller->getPseudoPath());
                }
                
                // If controller has main layout trying, we try wrap in it
                if (null !== $controller->getLayout()) {
                    $content = $this->render($controller->getLayout(), null, ['content' => $content]);
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