<?php

namespace Magium\Actions;

use Magium\WebDriver\WebDriver;

trait ByXpathTrait
{

    /**
     * @param WebDriver $webDriver
     * @param $selector
     * @return \Facebook\WebDriver\Remote\RemoteWebElement|null
     */

    protected function getElement(WebDriver $webDriver, $xpath)
    {
        $element = $webDriver->byXpath($xpath);
        return $element;
    }

}
