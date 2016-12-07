<?php

namespace Magium\Assertions\Css;

use Magium\Assertions\SelectorAssertionInterface;
use Magium\WebDriver\WebDriver;

class NotExists extends \Magium\Assertions\Element\NotExists implements SelectorAssertionInterface
{

    const ASSERTION = 'Css/NotExists';

    public function assertSelector($selector)
    {
        $this->setSelector($selector);
        $this->setBy(WebDriver::BY_CSS_SELECTOR);
        $this->assert();
    }

}
