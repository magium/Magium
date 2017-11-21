<?php

namespace Magium\Assertions\Browser;

use Magium\AbstractTestCase;
use Magium\Assertions\AbstractAssertion;
use Magium\Assertions\SelectorAssertionInterface;

class TitleEndsWith extends AbstractAssertion implements SelectorAssertionInterface
{
    use TitleTrait;

    const ASSERTION = 'Browser\TitleEndsWith';

    public function assert()
    {
        $title = $this->webDriver->getTitle();
        AbstractTestCase::assertNotNull($title);
        AbstractTestCase::assertNotNull($this->title);
        $title = trim($title);
        $pos = strpos($title, $this->title);
        AbstractTestCase::assertNotFalse($pos);
        $testEquals = $pos + strlen($this->title);
        $browserEquals = strlen($title);
        AbstractTestCase::assertEquals($testEquals, $browserEquals);
    }

}
