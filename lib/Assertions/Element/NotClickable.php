<?php

namespace Magium\Assertions\Element;

use Facebook\WebDriver\WebDriverBy;
use Magium\AbstractTestCase;
use Magium\InvalidTestTypeException;
use Magium\WebDriver\ExpectedCondition;

class NotClickable extends AbstractSelectorAssertion
{

    const ASSERTION = 'Element\NotClickable';

    public function assert()
    {
        $by = $this->getTestCase()->filterWebDriverAction($this->by);
        try {
            $this->webDriver->wait(1)->until(ExpectedCondition::elementToBeClickable(WebDriverBy::$by($this->selector)));
            $this->getTestCase()->fail(sprintf('The element %s, located with %s, is clickable but should not be', $this->selector, $by));
        } catch (\Exception $e) {
            // A failure is what we are hoping for.  It indicates that the element is not clickable, just like what the class wants
        }
    }

}