<?php

namespace Tests\Magium\Assertions;

use Magium\AbstractTestCase;
use Magium\Assertions\Url\Contains;
use Magium\Assertions\Url\Equals;
use Magium\Assertions\Url\IsUrl;
use Magium\Assertions\Url\NotContains;
use Magium\Assertions\Url\NotEquals;

class UrlAssertionTest extends AbstractTestCase
{

    public function testEquals()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $this->getAssertion(Equals::ASSERTION)->assertSelector('http://www.magiumlib.com/');
    }

    public function testNotEquals()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $this->getAssertion(NotEquals::ASSERTION)->assertSelector('http://www.eschrade.com/');
    }

    public function testContains()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $this->getAssertion(Contains::ASSERTION)->assertSelector('magiumlib');
    }

    public function testNotContains()
    {
        $this->commandOpen('http://www.magiumlib.com/');
        $this->getAssertion(NotContains::ASSERTION)->assertSelector('eschrade');
    }

    public function testIsUrl()
    {
        $this->getAssertion(IsUrl::ASSERTION)->assertSelector('http://www.magiumlib.com/');
    }

    public function testIsUrlFails()
    {
        $this->expectException(\PHPUnit_Framework_ExpectationFailedException::class);
        $this->getAssertion(IsUrl::ASSERTION)->assertSelector('a string');
    }
}
