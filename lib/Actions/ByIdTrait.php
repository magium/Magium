<?php

namespace Magium\Actions;

use Magium\WebDriver\WebDriver;

trait ByIdTrait
{

    /**
     * @param WebDriver $webDriver
     * @param $selector
     * @return \Facebook\WebDriver\Remote\RemoteWebElement|null
     */

    protected function getElement(WebDriver $webDriver, $selector)
    {
        $element = $webDriver->byId($selector);
        return $element;
    }

}
