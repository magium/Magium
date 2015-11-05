<?php

namespace Magium\WebDriver;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;


class WebDriver extends RemoteWebDriver
{
    const INSTRUCTION_MOUSE_MOVETO = 'mouseMoveTo';
    const INSTRUCTION_MOUSE_CLICK  = 'mouseClick';

    public function elementExists($selector, $by = 'byId')
    {
        try {
            $this->$by($selector);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function elementDisplayed($selector, $by = 'byId')
    {
        try {
            $element = $this->$by($selector);
            return $element->isDisplayed();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 
     * @param string $xpath
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    
    public function byXpath($xpath)
    {
        return $this->findElement(WebDriverBy::xpath($xpath));
    }
    
    /**
     * 
     * @param string $id
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    
    public function byId($id)
    {
        return $this->findElement(WebDriverBy::id($id));
    }
    
    /**
     * 
     * @param string $selector
     * @return \Facebook\WebDriver\Remote\RemoteWebElement
     */
    
    public function byCssSelector($selector)
    {
        return $this->findElement(WebDriverBy::cssSelector($selector));
    }
    
    public function __destruct()
    {
        $this->close();
    }
}