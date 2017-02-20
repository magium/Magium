<?php

namespace Tests\Magium\Assertions;

use Magium\AbstractTestCase;
use Magium\Assertions\Browser\CurrentUrlIsHttps;

class HttpsAssertionTest extends AbstractTestCase
{

    public function testHttpsAssertion()
    {
        $this->commandOpen('https://www.microsoft.com/');
        $assertion = $this->getAssertion(CurrentUrlIsHttps::ASSERTION);
        $assertion->assert();
    }


    public function testHttpsAssertionFails()
    {
        $this->commandOpen('https://www.eschrade.com/');
        $this->expectException(\PHPUnit_Framework_AssertionFailedError::class);
        $assertion = $this->getAssertion(CurrentUrlIsHttps::ASSERTION);
        $assertion->assert();
    }



}
