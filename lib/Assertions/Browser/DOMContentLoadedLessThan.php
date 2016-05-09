<?php

namespace Magium\Assertions\Browser;

use Magium\Assertions\AbstractAssertion;

class DOMContentLoadedLessThan extends AbstractAssertion
{
    const ASSERTION = 'Browser\DOMContentLoadedLessThan';

    protected $milli;

    public function setMaxElapsedMilliseconds($milli)
    {
        $this->milli = $milli;
    }

    public function assert()
    {
        $domContent = $this->webDriver->executeScript('return window.performance.timing');
        $timing = $domContent['domContentLoadedEventEnd'] - $domContent['connectStart'];
        $this->testCase->assertLessThanOrEqual($this->milli, $timing);
    }

}