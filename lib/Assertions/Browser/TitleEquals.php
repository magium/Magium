<?php

namespace Magium\Assertions\Browser;

use Magium\AbstractTestCase;
use Magium\Assertions\AbstractAssertion;
use Magium\Assertions\SelectorAssertionInterface;

class TitleEquals extends AbstractAssertion implements SelectorAssertionInterface
{
    use TitleTrait;

    const ASSERTION = 'Browser\TitleEquals';

    public function assert()
    {
        $title = $this->webDriver->getTitle();
        AbstractTestCase::assertNotNull($title);
        AbstractTestCase::assertNotNull($this->title);
        $title = trim($title);
        AbstractTestCase::assertEquals($title, $this->title);
    }

}
