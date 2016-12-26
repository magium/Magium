<?php

namespace Magium\Actions;

use Magium\WebDriver\WebDriver;

trait ByTextTrait
{

    /**
     * @param WebDriver $webDriver
     * @param $selector
     * @return \Facebook\WebDriver\Remote\RemoteWebElement|null
     */

    protected function getElement(WebDriver $webDriver, $selector)
    {
        $xpath = sprintf('//*[concat(" ", normalize-space(.), " ") = " %s "]', $selector);

        $element = $webDriver->byXpath($xpath);
        return $element;

    }

}
