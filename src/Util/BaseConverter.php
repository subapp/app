<?php

namespace Subapp\WebApp\Util;

/**
 * Class BaseConverter
 * @package Subapp\WebApp\Util
 */
class BaseConverter
{
    
    const USE_UPPERCASE    = 1;
    const USE_LOWERCASE    = 2;
    const USE_NUMS         = 4;
    const USE_NUMS_WO_ZERO = 8;
    
    /**
     * @var array
     */
    private $map = [];
    
    /**
     * @var int
     */
    private $length = 0;
    
    /**
     * BaseX constructor.
     * @param int $mode
     */
    public function __construct($mode = 0)
    {
        $this->map = ["\0"];
        
        if ($mode & self::USE_NUMS) {
            $this->map = array_merge($this->map, range('0', '9'));
        } elseif ($mode & self::USE_NUMS_WO_ZERO) {
            $this->map = array_merge($this->map, range('1', '9'));
        }
        
        if ($mode & self::USE_LOWERCASE) {
            $this->map = array_merge($this->map, range('a', 'z'));
        }
        
        if ($mode & self::USE_UPPERCASE) {
            $this->map = array_merge($this->map, range('A', 'Z'));
        }
        
        unset($this->map[0]);
        
        $this->map = array_map(function ($value) {
            return (string)$value;
        }, $this->map);
        
        $this->length = count($this->map);
    }
    
    /**
     * @return static
     */
    static public function instance()
    {
        static $instance;
        
        if (!$instance) {
            $mask = static::USE_LOWERCASE | static::USE_UPPERCASE | static::USE_NUMS;
            $instance = new BaseConverter($mask);
        }
        
        return $instance;
    }
    
    
    /**
     * @param array $characters
     * @return $this
     */
    public function append(array $characters)
    {
        $this->map = array_unique(array_merge($this->map, $characters));
        $this->length = count($this->map);
        
        return $this;
    }
    
    /**
     * @param array $characters
     * @return $this
     */
    public function prepend(array $characters)
    {
        $this->map = array_unique(array_merge($characters, $this->map));
        $this->length = count($this->map);
        
        return $this;
    }
    
    /**
     * @param int $value
     * @return int|string
     */
    public function encode($value = 0)
    {
        $encoded = current($this->map);
        
        if ($value > 0) {
            $encoded = null;
            do {
                $encoded = $this->map[bcmod($value, $this->length)] . $encoded;
                $value = bcdiv($value, $this->length, 0);
            } while ($value > 0);
        }
        
        return $encoded;
    }
    
    /**
     * @param string $data
     * @return int|string
     */
    public function decode($data = null)
    {
        $data = strrev($data);
        $string = implode($this->map);
        $decoded = 0;
        
        for ($i = 0, $length = strlen($data) - 1; $i <= $length; $i++) {
            $character = $data[$i];
            $position = strpos($string, $character);
            $decoded = bcadd($decoded, bcmul($position, bcpow($this->length, $i, 0), 0), 0);
        }
        
        return $decoded;
    }
    
}