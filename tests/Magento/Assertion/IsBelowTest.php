<?php

namespace Tests\Magento\Assertion;

use Magium\Magento\AbstractMagentoTestCase;


class IsBelowTest extends AbstractMagentoTestCase
{

    public function testElementIsBelowSucceeds()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $aboveElement = $this->byXpath('//a[.="About Us"]');
        $belowElement = $this->byXpath('//a[.="Contact Us"]');
        $assertion = $this->get('Magium\Assertions\Element\IsBelow');
        /* @var $assertion \Magium\Assertions\Element\IsBelow */
        $assertion->setAboveElement($aboveElement);
        $assertion->setBelowElement($belowElement);
        $assertion->assert(); // Should silently succeed.
    }


    public function testElementIsBelowFails()
    {
        $this->setExpectedException('PHPUnit_Framework_ExpectationFailedException');
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $aboveElement = $this->byXpath('//a[.="About Us"]');
        $belowElement = $this->byXpath('//a[.="Contact Us"]');
        $assertion = $this->get('Magium\Assertions\Element\IsBelow');
        /* @var $assertion \Magium\Assertions\Element\IsBelow */
        $assertion->setAboveElement($belowElement);
        $assertion->setBelowElement($aboveElement);
        $assertion->assert(); // Should silently succeed.
    }

}