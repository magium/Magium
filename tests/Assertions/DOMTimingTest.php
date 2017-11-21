<?php

namespace Tests\Magium\Assertions;

use Magium\AbstractTestCase;
use Magium\Assertions\Browser\DOMContentLoadedLessThan;
use Magium\Assertions\Browser\DOMPageLoadedLessThan;
use Magium\Assertions\Browser\TTFBLoadedLessThan;
use PHPUnit\Framework\ExpectationFailedException;

class DOMTiming extends AbstractTestCase
{

    public function testDOMContentLoadedLessThanInLessThanTenSecondsPasses()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $assertion = $this->getAssertion(DOMContentLoadedLessThan::ASSERTION);
        /* @var $assertion DOMContentLoadedLessThan */
        $assertion->setMaxElapsedMilliseconds(10000);
        $assertion->assert();
    }

    public function testDOMContentLoadedLessThanInLessThanOneMSPasses()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $assertion = $this->getAssertion(DOMContentLoadedLessThan::ASSERTION);
        /* @var $assertion DOMContentLoadedLessThan */
        $assertion->setMaxElapsedMilliseconds(1);
        $this->expectException('PHPUnit_Framework_ExpectationFailedException');
        // The page will have only been loaded once
        if (AbstractTestCase::isPHPUnit5()) {
            $this->expectException(\PHPUnit_Framework_ExpectationFailedException::class);
        } else {
            $this->expectException(ExpectationFailedException::class);
        }
        $assertion->assert();
    }

    public function testPageLoadedInLessThanTenSecondsPasses()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $assertion = $this->getAssertion(DOMPageLoadedLessThan::ASSERTION);
        /* @var $assertion DOMPageLoadedLessThan */
        $assertion->setMaxElapsedMilliseconds(10000);
        $assertion->assert();
    }

    public function testPageLoadedInLessThanOneMSPasses()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $assertion = $this->getAssertion(DOMPageLoadedLessThan::ASSERTION);
        /* @var $assertion DOMPageLoadedLessThan */
        $assertion->setMaxElapsedMilliseconds(1);
        if (AbstractTestCase::isPHPUnit5()) {
            $this->expectException(\PHPUnit_Framework_ExpectationFailedException::class);
        } else {
            $this->expectException(ExpectationFailedException::class);
        }
        $assertion->assert();
    }

    public function testTTFBInLessThanTenSecondsPasses()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $assertion = $this->getAssertion(TTFBLoadedLessThan::ASSERTION);
        /* @var $assertion TTFBLoadedLessThan */
        $assertion->setMaxElapsedMilliseconds(10000);
        $assertion->assert();
    }

    public function testTTFBInLessThanOneMSPasses()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $assertion = $this->getAssertion(TTFBLoadedLessThan::ASSERTION);
        /* @var $assertion TTFBLoadedLessThan */
        $assertion->setMaxElapsedMilliseconds(1);
        if (AbstractTestCase::isPHPUnit5()) {
            $this->expectException(\PHPUnit_Framework_ExpectationFailedException::class);
        } else {
            $this->expectException(ExpectationFailedException::class);
        }
        $assertion->assert();
    }

}
