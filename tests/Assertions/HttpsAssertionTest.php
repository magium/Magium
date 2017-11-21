<?php

namespace Tests\Magium\Assertions;

use Magium\AbstractTestCase;
use Magium\Assertions\Browser\CurrentUrlIsHttps;
use PHPUnit\Framework\AssertionFailedError;

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
        if (AbstractTestCase::isPHPUnit5()) {
            $this->expectException(\PHPUnit_Framework_AssertionFailedError::class);
        } else {
            $this->expectException(AssertionFailedError::class);
        }
        $assertion = $this->getAssertion(CurrentUrlIsHttps::ASSERTION);
        $assertion->assert();
    }



}
