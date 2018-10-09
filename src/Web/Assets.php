<?php

namespace Subapp\WebApp\Web;

use Subapp\Collection\ArrayCollection as Collection;
use Subapp\Html\Element\LinkElement;
use Subapp\Html\Element\ScriptElement;
use Subapp\Html\Element\StyleElement;
use Subapp\URI\Builder as Url;

/**
 * Class Assets
 * @package Subapp\Webapp\Web
 */
class Assets extends Collection
{
    
    /**
     * @var Assets
     */
    private $collection;
    
    /**
     * @var Collection
     */
    private $jsIncludes;
    
    /**
     * @var Collection
     */
    private $cssIncludes;
    
    /**
     * @var Collection
     */
    private $jsInline;
    
    /**
     * @var Collection
     */
    private $cssInline;
    
    /**
     * @var Url
     */
    private $url;
    
    /**
     * Assets constructor.
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        parent::__construct();
        
        $this->url = $url;
        
        $this->jsIncludes = new Collection();
        $this->cssIncludes = new Collection();
        $this->jsInline = new Collection();
        $this->cssInline = new Collection();
    }
    
    /**
     * @param $offset
     * @return Assets
     */
    public function collection($offset)
    {
        $collection = $this->collection[$offset];
        
        if (null === $collection) {
            $collection = new Assets($this->url);
            $this->collection->set($offset, $collection);
        }
        
        return $collection;
    }
    
    /**
     * @param $assetType
     * @param $assetRelative
     * @return $this
     */
    public function addInclude($assetType, $assetRelative)
    {
        switch ($assetType) {
            case 'css':
                $this->addCss($assetRelative);
                break;
            case 'js':
                $this->addJs($assetRelative);
                break;
            default:
                throw new \RuntimeException('Wrong asset type passed');
        }
        
        return $this;
    }
    
    /**
     * @param $source
     * @return Assets
     */
    public function addJsLocal($source)
    {
        return $this->addJs($source, false);
    }
    
    /**
     * @param $source
     * @return Assets
     */
    public function addCssLocal($source)
    {
        return $this->addCss($source, false);
    }
    
    /**
     * @param $source
     * @return Assets
     */
    public function addJsRemote($source)
    {
        return $this->addJs($source, true);
    }
    
    /**
     * @param $source
     * @return Assets
     */
    public function addCssRemote($source)
    {
        return $this->addCss($source, true);
    }
    
    /**
     * @param string $source
     * @param bool   $remote
     * @return $this
     */
    public function addJs($source, $remote = false)
    {
        if ($remote === false) {
            $source = $this->url->staticPath(sprintf('%s.js', $source));
        }
        
        $script = new ScriptElement();
        $script->setAttribute('src', $source);
        $script->setAttribute('type', 'text/javascript');
        
        $this->jsIncludes->append($script);
        
        return $this;
    }
    
    /**
     * @param string $source
     * @param bool   $remote
     * @return $this
     */
    public function addCss($source, $remote = false)
    {
        if ($remote === false) {
            $source = $this->url->staticPath(sprintf('%s.css', $source));
        }
        
        $linkCss = new LinkElement();
        $linkCss->setAttribute('href', $source);
        $linkCss->setAttribute('type', 'text/css');
        $linkCss->setAttribute('rel', 'stylesheet');
        
        $this->cssIncludes->append($linkCss);
        
        return $this;
    }
    
    /**
     * @param $jsScript
     * @return $this
     */
    public function addJsInline($jsScript)
    {
        $script = new ScriptElement();
        $script->setAttribute('type', 'text/javascript');
        $script->setContent(PHP_EOL . $jsScript . PHP_EOL);
        
        $this->jsInline->append($script);
        
        return $this;
    }
    
    /**
     * @param $cssStyles
     * @return $this
     */
    public function addCssInline($cssStyles)
    {
        $style = new StyleElement();
        $style->setAttribute('type', 'text/css');
        $style->setContent(PHP_EOL . $cssStyles . PHP_EOL);
        
        $this->cssInline->append($style);
        
        return $this;
    }
    
    /**
     * @param null $collectionName
     * @return string
     */
    public function renderCss($collectionName = null)
    {
        return sprintf(
            "<!-- Styles Include -->\n%s\n<!-- Styles Inline -->\n%s\n",
            implode(PHP_EOL, $this->cssIncludes->toArray()), implode(PHP_EOL, $this->cssInline->toArray())
        );
    }
    
    /**
     * @param null $collectionName
     * @return string
     */
    public function renderJs($collectionName = null)
    {
        return sprintf(
            "<!-- Script Include -->\n%s\n<!-- Script Inline -->\n%s\n",
            implode(PHP_EOL, $this->jsIncludes->toArray()), implode(PHP_EOL, $this->jsInline->toArray())
        );
    }
    
    /**
     * @return Collection
     */
    public function getJsIncludes()
    {
        return $this->jsIncludes;
    }
    
    /**
     * @return Collection
     */
    public function getCssIncludes()
    {
        return $this->cssIncludes;
    }
    
    /**
     * @return Collection
     */
    public function getJsInline()
    {
        return $this->jsInline;
    }
    
    /**
     * @return Collection
     */
    public function getCssInline()
    {
        return $this->cssInline;
    }
    
}