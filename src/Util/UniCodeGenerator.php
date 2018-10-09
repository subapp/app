<?php

namespace Subapp\WebApp\Util;

/**
 * Class UniCodeGenerator
 * @package Subapp\Webapp\Util
 */
class UniCodeGenerator
{
    
    const ANY     = 'A';
    const UPPER   = 'X';
    const LOWER   = 'x';
    const DIGIT   = '9';
    const SPECIAL = '#';
    
    /**
     * @var string
     */
    protected $pattern;
    
    /**
     * UniCodeGenerator constructor.
     * @param $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }
    
    /**
     * @return string
     */
    public function generate()
    {
        return implode($this->parsePattern());
    }
    
    /**
     * @return array
     */
    protected function parsePattern()
    {
        $generatedString = [];
        
        foreach (str_split($this->pattern) as $symbol) {
            if (in_array($symbol, [static::DIGIT, static::UPPER, static::LOWER, static::ANY, static::SPECIAL])) {
                
                switch ($symbol) {
                    
                    case static::ANY:
                        $random = rand(0, 1) ? $this->getRandomChar() : $this->getRandomDigit();
                        $random = rand(0, 1) ? $random : strtoupper($random);
                        
                        $generatedString[] = $random;
                        break;
                    
                    case static::UPPER:
                        $generatedString[] = strtoupper($this->getRandomChar());
                        break;
                    
                    case static::LOWER:
                        $generatedString[] = $this->getRandomChar();
                        break;
                    
                    case static::DIGIT:
                        $generatedString[] = $this->getRandomDigit();
                        break;
                    
                    case static::SPECIAL:
                        $generatedString[] = $this->getRandomSpecial();
                        break;
                }
                
            } else {
                $generatedString[] = $symbol;
            }
        }
        
        return $generatedString;
    }
    
    /**
     * @return string
     */
    protected function getRandomChar()
    {
        $array = range('a', 'z');
        $randomKey = array_rand($array);
        
        return $array[$randomKey];
    }
    
    /**
     * @return int
     */
    protected function getRandomDigit()
    {
        return mt_rand(0, 9);
    }
    
    /**
     * @return string
     */
    protected function getRandomSpecial()
    {
        $array = range("\x21", "\x2f");
        $randomKey = array_rand($array);
        
        return $array[$randomKey];
    }
    
}