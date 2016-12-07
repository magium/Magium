<?php

namespace Magium\Assertions\Id;

use Magium\Assertions\SelectorAssertionInterface;
use Magium\WebDriver\WebDriver;

class Displayed extends \Magium\Assertions\Element\Displayed  implements SelectorAssertionInterface
{
    const ASSERTION = 'Id/Displayed';

    public function assertSelector($selector)
    {
        $this->setSelector($selector);
        $this->setBy(WebDriver::BY_ID);
        $this->assert();
    }

}
