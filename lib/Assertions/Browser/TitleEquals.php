<?php

namespace Magium\Assertions\Browser;

use Magium\Assertions\AbstractAssertion;
use Magium\Assertions\SelectorAssertionInterface;

class TitleEquals extends AbstractAssertion implements SelectorAssertionInterface
{
    use TitleTrait;

    const ASSERTION = 'Browser\TitleEquals';

    public function assert()
    {
        $title = $this->webDriver->getTitle();
        \PHPUnit_Framework_TestCase::assertNotNull($title);
        \PHPUnit_Framework_TestCase::assertNotNull($this->title);
        $title = trim($title);
        \PHPUnit_Framework_TestCase::assertEquals($title, $this->title);
    }

}
