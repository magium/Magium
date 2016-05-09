<?php

namespace Magium\Assertions\Browser;

use Magium\Assertions\AbstractAssertion;

class LogEmpty extends AbstractAssertion
{

    const ASSERTION = 'Browser\LogEmpty';

    public function assert()
    {
        $log = $this->webDriver->manage()->getLog('browser');
        $this->testCase->assertCount(0, $log);
    }

}