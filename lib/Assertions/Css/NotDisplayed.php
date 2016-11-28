<?php

namespace Magium\Assertions\Css;

use Magium\Assertions\SelectorAssertionInterface;
use Magium\WebDriver\WebDriver;

class NotDisplayed extends \Magium\Assertions\Element\NotDisplayed  implements SelectorAssertionInterface
{

    public function assertSelector($selector)
    {
        $this->setSelector($selector);
        $this->setBy(WebDriver::BY_CSS_SELECTOR);
        $this->assert();
    }

}