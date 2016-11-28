<?php

namespace Magium\Assertions\Xpath;

use Magium\Assertions\SelectorAssertionInterface;
use Magium\WebDriver\WebDriver;

class NotDisplayed extends \Magium\Assertions\Element\NotDisplayed  implements SelectorAssertionInterface
{

    public function assertSelector($selector)
    {
        $this->setSelector($selector);
        $this->setBy(WebDriver::BY_XPATH);
        $this->assert();
    }

}