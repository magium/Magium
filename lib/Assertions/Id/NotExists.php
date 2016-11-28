<?php

namespace Magium\Assertions\Id;

use Magium\Assertions\SelectorAssertionInterface;
use Magium\WebDriver\WebDriver;

class NotExists extends \Magium\Assertions\Element\NotExists implements SelectorAssertionInterface
{

    public function assertSelector($selector)
    {
        $this->setSelector($selector);
        $this->setBy(WebDriver::BY_ID);
        $this->assert();
    }

}