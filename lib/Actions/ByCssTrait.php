<?php

namespace Magium\Actions;

use Magium\WebDriver\WebDriver;

trait ByCssTrait
{

    /**
     * @param WebDriver $webDriver
     * @param $selector
     * @return \Facebook\WebDriver\Remote\RemoteWebElement|null
     */

    protected function getElement(WebDriver $webDriver, $selector)
    {
        $element = $webDriver->byCssSelector($selector);
        return $element;
    }

}
