<?php

namespace Magium\Assertions\Element;

use Facebook\WebDriver\WebDriverElement;
use Magium\Assertions\AbstractAssertion;
use Magium\Assertions\AssertionInterface;

class IsBelow extends AbstractAssertion
{
    const ASSERTION = 'Element\IsBelow';

    /**
     * @var \Facebook\WebDriver\WebDriverElement
     */
    protected $aboveElement;

    /**
     * @var \Facebook\WebDriver\WebDriverElement
     */

    protected $belowElement;

    public function setAboveElement(WebDriverElement $element)
    {
        $this->aboveElement = $element;
    }

    public function setBelowElement(WebDriverElement $element)
    {
        $this->belowElement = $element;
    }

    public function assert()
    {

        $aboveCoord = $this->aboveElement->getLocation();
        $belowCoord = $this->belowElement->getLocation();
        $this->getTestCase()->assertGreaterThan($aboveCoord->getY(), $belowCoord->getY(), 'The "below" element was not below the "above" element');
    }

}