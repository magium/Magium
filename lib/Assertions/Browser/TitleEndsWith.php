<?php

namespace Magium\Assertions\Browser;

use Magium\Assertions\AbstractAssertion;
use Magium\Assertions\SelectorAssertionInterface;

class TitleEndsWith extends AbstractAssertion implements SelectorAssertionInterface
{
    use TitleTrait;

    const ASSERTION = 'Browser\TitleEndsWith';

    public function assert()
    {
        $title = $this->webDriver->getTitle();
        \PHPUnit_Framework_TestCase::assertNotNull($title);
        \PHPUnit_Framework_TestCase::assertNotNull($this->title);
        $title = trim($title);
        $pos = strpos($title, $this->title);
        \PHPUnit_Framework_TestCase::assertNotFalse($pos);
        $testEquals = $pos + strlen($this->title);
        $browserEquals = strlen($title);
        \PHPUnit_Framework_TestCase::assertEquals($testEquals, $browserEquals);
    }

}
