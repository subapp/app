<?php

namespace Colibri\WebApp\Grid;

/**
 * Class GridFilter
 *
 * @package Colibri\Webapp\Grid
 */
class GridFilter
{
    
    /**
     * @var array
     */
    protected $parameters = [];
    
    /**
     * @var array
     */
    protected $filters = [];
    
    /**
     * @var Grid
     */
    protected $grid;
    
    /**
     * GridFilter constructor.
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
        $this->filters = $grid->getFilters();
        $this->prepareParameters($this->getFilters());
    }
    
    /**
     * @param string $column
     * @param mixed  $value
     * @param string $filter
     * @return $this
     */
    public function filter($column, $value, $filter = Grid::EQ)
    {
        $filters = $this->getFilters();
        
        $filters[$column][$filter][$value] = $value;
        
        $this->prepareParameters($filters);
        
        return $this;
    }
    
    /**
     * @param        $column
     * @param        $value
     * @param string $filter
     * @return $this
     */
    public function append($column, $value, $filter = Grid::EQ)
    {
        $filters = $this->getFilters();
        
        $filters[$column][$filter][$value] = $value;
        
        $this->filters = $filters;
        
        $this->prepareParameters($filters);
        
        return $this;
    }
    
    /**
     * @param        $column
     * @param        $value
     * @param string $filter
     * @return $this
     */
    public function rewrite($column, $value, $filter = Grid::EQ)
    {
        $this->cleanup(Grid::CLEAN_COLUMN, $column);
        
        return $this->filter($column, $value, $filter);
    }
    
    /**
     * @param $column
     * @param $value
     * @return $this
     */
    public function delete($column, $value)
    {
        return $this->cleanup(Grid::CLEAN_VALUE, $column, $value);
    }
    
    /**
     * @param int  $reset
     * @param null $column
     * @param null $value
     * @return $this
     */
    public function cleanup($reset = Grid::CLEAN_ALL, $column = null, $value = null)
    {
        switch ($reset) {
            
            case Grid::CLEAN_ALL:
                $this->filters = [];
                break;
            
            case Grid::CLEAN_COLUMN:
                unset($this->filters[$column]);
                break;
            
            case Grid::CLEAN_VALUE:
                
                if (isset($this->filters[$column]) && count($this->filters[$column]) > 0) {
                    foreach ($this->filters[$column] as $filterName => $columnFilters) {
                        if (false !== ($index = array_search($value, $columnFilters))) {
                            unset($this->filters[$column][$filterName][$index]);
                        }
                    }
                }
                
                break;
        }
        
        $this->prepareParameters($this->getFilters());
        
        return $this;
    }
    
    /**
     * @param array $filters
     * @return $this
     */
    public function prepareParameters(array $filters)
    {
        $this->parameters = [];
        
        foreach ($filters as $columnName => $columnFilters) {
            
            $filterParts = [];
            foreach ($columnFilters as $name => $filter) {
                if (count($filter) > 0) {
                    $filterParts[] = sprintf('%s%s%s', $name, $this->getGrid()->getSeparator(), implode($this->getGrid()->getSeparator(), $filter));
                }
            }
            
            $this->parameters[$columnName] = implode($this->getGrid()->getSeparator(), $filterParts);
        }
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function render()
    {
        $grid = $this->getGrid();
        
        $columnFilters = [];
        foreach ($this->getParameters() as $column => $filter) {
            $columnFilters[] = sprintf('%s/%s', $column, $filter);
        }
        
        $filterPath = implode('/', $columnFilters);
        
        $path = $this->hasParameters()
            ? sprintf('%s/%s/%s', $grid->getPrefixPath(), $grid->getFilterMarker(), $filterPath) : $grid->getPrefixPath();
        
        return $grid->getUrl()->path($path);
    }
    
    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
    
    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }
    
    /**
     * @return bool
     */
    public function hasFilters()
    {
        return count($this->filters) > 0;
    }
    
    /**
     * @return bool
     */
    public function hasParameters()
    {
        return count($this->parameters) > 0;
    }
    
    /**
     * @return Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
    
}