<?php

namespace Magium\WebDriver;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;

class WebDriverElementProxy implements WebDriverElement
{

    protected $webDriver;
    protected $selector;
    protected $by;
    protected $element;

    public function __construct(
        WebDriver $webDriver,
        $selector,
        $by = WebDriver::BY_ID
    )
    {
        $this->webDriver = $webDriver;
        $this->selector = $selector;
        $this->by = $by;
    }

    public function __call($name, $arguments)
    {
        if (strpos($name, '::') > 0) {
            list(   , $name) = explode('::', $name);
        }
        if (!$this->element instanceof WebDriverElement) {
            $this->element = $this->webDriver->{$this->by}($this->selector);
        }
        $return = call_user_func_array([$this->element, $name], $arguments);
        return $return;
    }

    public function clear()
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function click()
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function getAttribute($attribute_name)
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function getCSSValue($css_property_name)
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function getLocation()
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function getLocationOnScreenOnceScrolledIntoView()
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function getSize()
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function getTagName()
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function getText()
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function isDisplayed()
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function isEnabled()
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function isSelected()
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function sendKeys($value)
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function submit()
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function getID()
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function findElement(WebDriverBy $locator)
    {
        return $this->__call(__METHOD__, func_get_args());
    }

    public function findElements(WebDriverBy $locator)
    {
        return $this->__call(__METHOD__, func_get_args());
    }


}