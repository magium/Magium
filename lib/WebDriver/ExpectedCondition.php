<?php

namespace Magium\WebDriver;

use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverExpectedCondition;

class ExpectedCondition extends WebDriverExpectedCondition
{

    public static function elementExists($selector, $by = 'byId')
    {
        return new WebDriverExpectedCondition(
            function ($driver) use ($selector, $by) {
                try {
                    $element = $driver->$by($selector);
                    return $element instanceof WebDriverElement;
                } catch (\Exception $e) {
                    // No need to do anything.  No element == exception.
                }
                return false;
            }
        );
    }

    public static function elementRemoved(WebDriverElement $element)
    {
        return new WebDriverExpectedCondition(
            function () use ($element) {
                try {
                    $element->isDisplayed();
                    return false;
                } catch (\Exception $e) {
                    return true;
                }
            }
        );
    }

}