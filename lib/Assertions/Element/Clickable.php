<?php

namespace Magium\Assertions\Element;

use Facebook\WebDriver\WebDriverBy;
use Magium\AbstractTestCase;
use Magium\InvalidTestTypeException;
use Magium\WebDriver\ExpectedCondition;

class Clickable extends AbstractSelectorAssertion
{

    const ASSERTION = 'Element\Clickable';

    public function assert()
    {
        $by = $this->getTestCase()->filterWebDriverAction($this->by);
        try {
            $this->webDriver->wait(1)->until(ExpectedCondition::elementToBeClickable(WebDriverBy::$by($this->selector)));
        } catch (\Exception $e) {
            $this->getTestCase()->fail(sprintf('The element %s, located with %s, cannot be clicked', $this->selector, $by));
        }
    }

}