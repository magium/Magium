<?php

namespace Magium\Assertions\Browser;

use Magium\Assertions\AbstractAssertion;

class DOMPageLoadedLessThan extends AbstractAssertion
{
    const ASSERTION = 'Browser\DOMPageLoadedLessThan';

    protected $milli;

    public function setMaxElapsedMilliseconds($milli)
    {
        $this->milli = $milli;
    }

    public function assert()
    {
        $domContent = $this->webDriver->executeScript('return window.performance.timing');
        $timing = $domContent['loadEventEnd'] - $domContent['connectStart'];
        $this->testCase->assertLessThanOrEqual($this->milli, $timing);
    }

}