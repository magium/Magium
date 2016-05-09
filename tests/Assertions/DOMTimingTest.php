<?php

namespace Tests\Magium\Assertions;

use Magium\AbstractTestCase;
use Magium\Assertions\Browser\DOMContentLoadedLessThan;
use Magium\Assertions\Browser\DOMPageLoadedLessThan;

class DOMTiming extends AbstractTestCase
{

    public function testDOMContentLoadedLessThanInLessThanFiveSecondsPasses()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $assertion = $this->getAssertion(DOMContentLoadedLessThan::ASSERTION);
        /* @var $assertion DOMContentLoadedLessThan */
        $assertion->setMaxElapsedMilliseconds(5000);
        $assertion->assert();
    }

    public function testDOMContentLoadedLessThanInLessThanOneMSPasses()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $assertion = $this->getAssertion(DOMContentLoadedLessThan::ASSERTION);
        /* @var $assertion DOMContentLoadedLessThan */
        $assertion->setMaxElapsedMilliseconds(1);
        $this->setExpectedException('PHPUnit_Framework_ExpectationFailedException');
        $assertion->assert();
    }

    public function testPageLoadedInLessThanFiveSecondsPasses()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $assertion = $this->getAssertion(DOMPageLoadedLessThan::ASSERTION);
        /* @var $assertion DOMPageLoadedLessThan */
        $assertion->setMaxElapsedMilliseconds(5000);
        $assertion->assert();
    }

    public function testPageLoadedInLessThanOneMSPasses()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $assertion = $this->getAssertion(DOMPageLoadedLessThan::ASSERTION);
        /* @var $assertion DOMPageLoadedLessThan */
        $assertion->setMaxElapsedMilliseconds(1);
        $this->setExpectedException('PHPUnit_Framework_ExpectationFailedException');
        $assertion->assert();
    }

}