<?php

namespace Magium\Assertions\Element;

use Facebook\WebDriver\WebDriverElement;
use Magium\Assertions\AbstractAssertion;

class IsRight extends AbstractAssertion
{
    const ASSERTION = 'Element\IsRight';

    /**
     * @var \Facebook\WebDriver\WebDriverElement
     */
    protected $rightElement;

    /**
     * @var \Facebook\WebDriver\WebDriverElement
     */

    protected $leftElement;


    public function setRightElement(WebDriverElement $element)
    {
        $this->rightElement = $element;
    }

    public function setLeftElement(WebDriverElement $element)
    {
        $this->leftElement = $element;
    }

    public function assert()
    {

        $rightCoord = $this->rightElement->getLocation();
        $leftCoord = $this->leftElement->getLocation();
        $this->getTestCase()->assertGreaterThan($leftCoord->getX(), $rightCoord->getX(), 'The "right" element was not to the right of the "left" element');
    }

}