<?php

namespace Magium\Assertions\Element;

use Facebook\WebDriver\WebDriverElement;
use Magium\AbstractTestCase;
use Magium\Assertions\AssertionInterface;

class IsBelow implements AssertionInterface
{

    /**
     * @var \Facebook\WebDriver\WebDriverElement
     */
    protected $aboveElement;

    /**
     * @var \Facebook\WebDriver\WebDriverElement
     */

    protected $belowElement;
    protected $testCase;

    public function __construct(
        AbstractTestCase $testCase
    )
    {
        $this->testCase = $testCase;
    }

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
        $this->testCase->assertWebDriverElement($this->aboveElement);
        $this->testCase->assertWebDriverElement($this->belowElement);
        $aboveCoord = $this->aboveElement->getLocation();
        $belowCoord = $this->belowElement->getLocation();
        $this->testCase->assertGreaterThan($aboveCoord->getY(), $belowCoord->getY(), 'The "below" element was not below the "above" element');
    }

}