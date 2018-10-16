<?php

namespace Subapp\WebApp\Web\Html\Element;

use Subapp\WebApp\Web\Html\HtmlElement;

/**
 * Class SelectElement
 *
 * @package Subapp\WebApp\Web\Html\Element
 */
class SelectElement extends HtmlElement
{
  
  /**
   * @var array
   */
  protected $values = [];
  
  /**
   * @var array
   */
  protected $selectedValues = [];

  /**
   * SelectElement constructor.
   *
   * @param null  $name
   * @param array $values
   * @param null  $selectedValue
   * @param array $attributes
   */
  public function __construct($name = null, array $values = [], $selectedValue = null, array $attributes = [])
  {
    parent::__construct('select', $attributes, null);

    $this->setAttribute('name', $name);
    $this->values = $values;
    
    if (null !== $selectedValue) {
      $this->selectedValues[] = $selectedValue;
    }
  }
  
  /**
   * @return $this
   */
  protected function generateOptions()
  {
    foreach ($this->getValues() as $value => $label) {
      if (is_array($label)) {
        $optgroup = new OptgroupElement(null, ['label' => $value]);
        
        foreach ($label as $innerValue => $innerLabel) {
          $this->appendOptionElement($optgroup, $innerValue, $innerLabel);
        }
      
        $this->appendContent($optgroup);
      } else {
        $this->appendOptionElement($this, $value, $label);
      }
    }
    
    return $this;
  }
  
  /**
   * @param HtmlElement $htmlElement
   * @param $value
   * @param $label
   * @return $this
   */
  protected function appendOptionElement(HtmlElement $htmlElement, $value, $label)
  {
    $option = new OptionElement($label, ['value' => $value]);
  
    if ($this->isSelected($value)) {
      $option->setAttribute('selected', 'selected');
    }
  
    $htmlElement->appendContent($option);
    
    return $this;
  }
  
  /**
   * @param $value
   * @return bool
   */
  protected function isSelected($value)
  {
    return in_array($value, $this->getSelectedValues());
  }

  /**
   * @return string
   */
  public function render()
  {
    $this->generateOptions();

    return parent::render();
  }

  /**
   * @return string
   */
  public function getName()
  {
    return $this->getAttribute('name');
  }
  
  /**
   * @param $name
   * @return $this
   */
  public function setName($name)
  {
    $this->setAttribute('name', $name);

    return $this;
  }
  
  /**
   * @return array
   */
  public function getValues()
  {
    return $this->values;
  }
  
  /**
   * @param array $values
   * @return $this
   */
  public function setValues($values)
  {
    $this->values = $values;
    
    return $this;
  }
  
  /**
   * @param array $values
   * @return $this
   */
  public function addValues(array $values)
  {
    $this->values = $this->values + $values;
    
    return $this;
  }
  
  /**
   * @return array
   */
  public function getSelectedValues()
  {
    return $this->selectedValues;
  }
  
  /**
   * @param array $selectedValues
   * @return $this
   */
  public function setSelectedValues($selectedValues)
  {
    $this->selectedValues = $selectedValues;
    
    return $this;
  }
  
  /**
   * @param $value
   * @return $this
   */
  public function addSelectedValue($value)
  {
    $this->selectedValues[] = $value;
    
    return $this;
  }
  
  /**
   * @return SelectElement
   */
  public function clearSelectedValues()
  {
    return $this->setSelectedValues([]);
  }

}