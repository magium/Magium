<?php

namespace Magium\Assertions\Id;

use Magium\Assertions\SelectorAssertionInterface;
use Magium\WebDriver\WebDriver;

class Exists extends \Magium\Assertions\Element\Exists implements SelectorAssertionInterface
{

    const ASSERTION = 'Id/Exists';

    public function assertSelector($selector)
    {
        $this->setSelector($selector);
        $this->setBy(WebDriver::BY_ID);
        $this->assert();
    }

}
