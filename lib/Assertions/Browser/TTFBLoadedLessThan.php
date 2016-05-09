<?php

namespace Magium\Assertions\Browser;

use Magium\Assertions\AbstractAssertion;

class TTFBLoadedLessThan extends AbstractAssertion
{
    const ASSERTION = 'Browser\TTFBLoadedLessThan';

    protected $milli;

    public function setMaxElapsedMilliseconds($milli)
    {
        $this->milli = $milli;
    }

    public function assert()
    {
        $domContent = $this->webDriver->executeScript('return window.performance.timing');
        $timing = $domContent['responseStart'] - $domContent['connectStart'];
        $this->testCase->assertLessThanOrEqual($this->milli, $timing);
    }

}