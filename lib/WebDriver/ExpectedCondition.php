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

}