<?php

namespace Subapp\WebApp\Web;

use Subapp\Collection\ArrayCollection;
use Subapp\Html\HtmlElement;

/**
 * Class Metatag
 * @package Subapp\Webapp\Web
 */
class Metatag
{
    
    /**
     * @var ArrayCollection
     */
    protected $metatags;
    
    /**
     * Metatag constructor.
     */
    public function __construct()
    {
        $this->metatags = new ArrayCollection();
    }
    
    /**
     * @param       $name
     * @param array $attributes
     * @return HtmlElement
     */
    public function setTagName($name, array $attributes = [])
    {
        $tag = new HtmlElement($name, $attributes, null);
        $hash = $this->getTagHashCode($name, $attributes);
        
        $this->metatags->offsetSet($hash, $tag);
        
        return $tag;
    }
    
    /**
     * @param       $name
     * @param array $attributes
     * @return string
     */
    public function getTagHashCode($name, array $attributes)
    {
        return sha1(json_encode([$name, $attributes]));
    }
    
    /**
     * @param       $name
     * @param array $attributes
     * @return $this
     */
    public function removeTag($name, array $attributes)
    {
        $hash = $this->getTagHashCode($name, $attributes);
        $this->metatags->offsetUnset($hash);
        
        return $this;
    }
    
    /**
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        $titleTag = $this->setTagName('title', []);
        
        $titleTag->setContent($title);
        
        return $this;
    }
    
    /**
     * @param string $title
     * @param bool   $prepend
     * @param string $separator
     */
    public function addTitle($title, $prepend = true, $separator = '—')
    {
        /** @var HtmlElement $titleTag */
        
        $hash = $this->getTagHashCode('title', []);
        
        if (!$this->metatags->offsetExists($hash)) {
            $this->setTagName('title', []);
        }
        
        $titleTag = $this->metatags->offsetGet($hash);
        
        $contents = $titleTag->getContent();
        $prepend ? array_unshift($contents, $title) : array_push($contents, $title);
        
        $titleTag->setContent(join(sprintf(' %s ', $separator), $contents));
    }
    
    /**
     * @param array $attributes
     * @return $this
     */
    public function setMetatag(array $attributes)
    {
        $this->setTagName('meta', $attributes)->setSingle(true);
        
        return $this;
    }
    
    /**
     * @param array $keywords
     * @return $this
     */
    public function setKeywords(array $keywords)
    {
        $this->setMetatag(['name' => 'keywords', 'content' => implode(',', $keywords),]);
        
        return $this;
    }
    
    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->setMetatag(['name' => 'description', 'content' => $description,]);
        
        return $this;
    }
    
    /**
     * @param string $imagePath
     * @return $this
     */
    public function setOgImage($imagePath)
    {
        $this->setMetatag(['property' => 'og:image', 'content' => $imagePath,]);
        
        return $this;
    }
    
    /**
     * @param string $siteName
     * @return $this
     */
    public function setOgSiteName($siteName)
    {
        $this->setMetatag(['property' => 'og:site_name', 'content' => $siteName,]);
        
        return $this;
    }
    
    /**
     * @param string $title
     * @return $this
     */
    public function setOgTitle($title)
    {
        $this->setMetatag(['property' => 'og:title', 'content' => $title,]);
        
        return $this;
    }
    
    /**
     * @param string $description
     * @return $this
     */
    public function setOgDescription($description)
    {
        $this->setMetatag(['property' => 'og:description', 'content' => $description,]);
        
        return $this;
    }
    
    /**
     * @param string $name
     * @return $this
     */
    public function setTwitterCard($name)
    {
        $this->setMetatag(['name' => 'twitter:card', 'content' => $name,]);
        
        return $this;
    }
    
    /**
     * @param string $title
     * @return $this
     */
    public function setTwitterTitle($title)
    {
        $this->setMetatag(['name' => 'twitter:title', 'content' => $title,]);
        
        return $this;
    }
    
    /**
     * @param string $description
     * @return $this
     */
    public function setTwitterDescription($description)
    {
        $this->setMetatag(['name' => 'twitter:description', 'content' => $description,]);
        
        return $this;
    }
    
    /**
     * @param string $imagePath
     * @return $this
     */
    public function setTwitterImage($imagePath)
    {
        $this->setMetatag(['name' => 'twitter:image', 'content' => $imagePath,]);
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function render()
    {
        return sprintf("%s\n", implode("\n", $this->metatags->toArray()));
    }
    
}