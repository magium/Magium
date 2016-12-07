<?php

namespace Magium\Assertions\Browser;

use Magium\Assertions\AbstractAssertion;
use Magium\Assertions\SelectorAssertionInterface;

class TitleNotContains extends AbstractAssertion implements SelectorAssertionInterface
{
    use TitleTrait;

    const ASSERTION = 'Browser\TitleNotContains';

    public function assert()
    {
        $title = $this->webDriver->getTitle();
        \PHPUnit_Framework_TestCase::assertNotNull($title);
        \PHPUnit_Framework_TestCase::assertNotNull($this->title);
        $title = trim($title);
        $pos = strpos($title, $this->title);
        \PHPUnit_Framework_TestCase::assertFalse($pos);

    }

}
