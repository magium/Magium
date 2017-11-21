<?php

namespace Magium\Assertions\Browser;

use Magium\AbstractTestCase;
use Magium\Assertions\AbstractAssertion;
use Magium\Assertions\SelectorAssertionInterface;

class TitleContains extends AbstractAssertion implements SelectorAssertionInterface
{
    use TitleTrait;

    const ASSERTION = 'Browser\TitleContains';

    public function assert()
    {
        $title = $this->webDriver->getTitle();
        AbstractTestCase::assertNotNull($title);
        AbstractTestCase::assertNotNull($this->title);
        $title = trim($title);
        $pos = strpos($title, $this->title);
        AbstractTestCase::assertNotFalse($pos);

    }

}
