<?php

namespace Magium\Assertions\Text;

use Magium\Assertions\SelectorAssertionInterface;
use Magium\WebDriver\WebDriver;

class NotExists extends \Magium\Assertions\Element\NotExists implements SelectorAssertionInterface
{

    const ASSERTION = 'Text\NotExists';

    use TextTrait;

    public function assertSelector($selector)
    {
        $selector = $this->createXpath($selector);
        $this->setSelector($selector);
        $this->setBy(WebDriver::BY_XPATH);
        $this->assert();
    }

}
