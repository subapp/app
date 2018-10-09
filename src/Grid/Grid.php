<?php

namespace Subapp\WebApp\Grid;

use Subapp\URI\Builder as Url;

/**
 *
 * Class Grid
 * @package Subapp\Webapp\Grid
 */
class Grid
{
    
    const DEFAULT_MARKER = 'filter';
    
    const LIKE     = 'lk';
    const NOT_LIKE = 'nl';
    const EQ       = 'eq';
    const NE       = 'ne';
    const GT       = 'gt';
    const GE       = 'ge';
    const LT       = 'lt';
    const LE       = 'le';
    
    const SEPARATOR_DASH       = '-';
    const SEPARATOR_UNDERSCORE = '_';
    
    const CLEAN_ALL    = 1;
    const CLEAN_COLUMN = 2;
    const CLEAN_VALUE  = 4;
    
    /**
     * @var array
     */
    protected $allowFilterNames = [
        self::LIKE,
        self::NOT_LIKE,
        self::EQ,
        self::NE,
        self::GT,
        self::GE,
        self::LT,
        self::LE,
    ];
    
    protected $separator = self::SEPARATOR_DASH;
    
    /**
     * @var string
     */
    protected $prefixPath;
    
    /**
     * @var Url
     */
    protected $url;
    
    /**
     * @var array
     */
    protected $filters = [];
    
    /**
     * @var string
     */
    protected $filterMarker = self::DEFAULT_MARKER;
    
    /**
     * Grid constructor.
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }
    
    /**
     * @param null $requestString
     * @return $this
     */
    public function processRequest($requestString = null)
    {
        // cleanup request string
        $parameters = explode('/', trim($requestString, '/'));
        $index = array_search($this->getFilterMarker(), $parameters);
        
        if (false !== $index) {
            
            $pairs = array_chunk(array_slice($parameters, $index + 1), 2);
            
            foreach ($pairs as $pair) {
                
                list($column, $filters) = $pair;
                $filters = explode($this->getSeparator(), $filters);
                
                $filterName = Grid::EQ;
                while ($filter = array_shift($filters)) {
                    
                    if (in_array($filter, $this->getAllowFilterNames(), true)) {
                        $filterName = $filter;
                        continue;
                    }
                    
                    $this->addFilter($column, $filterName, $filter);
                }
                
            }
        }
        
        return $this;
    }
    
    /**
     * @param $column
     * @param $filterName
     * @param $filter
     * @return $this
     */
    public function addFilter($column, $filterName, $filter)
    {
        $this->filters[$column][$filterName][$filter] = $filter;
        
        return $this;
    }
    
    
    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }
    
    /**
     * @return array
     */
    public function getAllowFilterNames()
    {
        return $this->allowFilterNames;
    }
    
    /**
     * @return string
     */
    public function getFilterMarker()
    {
        return $this->filterMarker;
    }
    
    /**
     * @param string $filterMarker
     */
    public function setFilterMarker($filterMarker)
    {
        $this->filterMarker = $filterMarker;
    }
    
    /**
     * @return string
     */
    public function getPrefixPath()
    {
        return $this->prefixPath;
    }
    
    /**
     * @param string $prefixPath
     */
    public function setPrefixPath($prefixPath)
    {
        $this->prefixPath = $prefixPath;
    }
    
    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * @param Url $url
     */
    public function setUrl(Url $url)
    {
        $this->url = $url;
    }
    
    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->separator;
    }
    
    /**
     * @param string $separator
     */
    public function setSeparator($separator)
    {
        $this->separator = $separator;
    }
    
    /**
     * @return GridFilter
     */
    public function getGridFilter()
    {
        return new GridFilter($this);
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getGridFilter()->render();
    }
    
}