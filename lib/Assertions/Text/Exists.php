<?php

namespace Magium\Assertions\Text;

use Magium\Assertions\SelectorAssertionInterface;
use Magium\WebDriver\WebDriver;

class Exists extends \Magium\Assertions\Element\Exists implements SelectorAssertionInterface
{
    const ASSERTION = 'Text\Exists';

    use TextTrait;

    public function assertSelector($selector)
    {
        $selector = $this->createXpath($selector);
        $this->setSelector($selector);
        $this->setBy(WebDriver::BY_XPATH);
        $this->assert();
    }

}
