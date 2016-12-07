<?php

namespace Magium\Assertions\Id;

use Magium\Assertions\SelectorAssertionInterface;
use Magium\WebDriver\WebDriver;

class NotDisplayed extends \Magium\Assertions\Element\NotDisplayed  implements SelectorAssertionInterface
{

    const ASSERTION = 'Id/NotDisplayed';

    public function assertSelector($selector)
    {
        $this->setSelector($selector);
        $this->setBy(WebDriver::BY_ID);
        $this->assert();
    }

}
