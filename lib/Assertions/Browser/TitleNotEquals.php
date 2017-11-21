<?php

namespace Magium\Assertions\Browser;

use Magium\AbstractTestCase;
use Magium\Assertions\AbstractAssertion;
use Magium\Assertions\SelectorAssertionInterface;

class TitleNotEquals extends AbstractAssertion implements SelectorAssertionInterface
{
    use TitleTrait;

    const ASSERTION = 'Browser\TitleNotEquals';

    public function assert()
    {
        $title = $this->webDriver->getTitle();
        AbstractTestCase::assertNotNull($title);
        AbstractTestCase::assertNotNull($this->title);
        $title = trim($title);
        AbstractTestCase::assertNotEquals($title, $this->title);
    }

}
