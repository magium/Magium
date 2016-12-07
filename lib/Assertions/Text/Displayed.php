<?php

namespace Magium\Assertions\Text;

use Magium\Assertions\SelectorAssertionInterface;
use Magium\WebDriver\WebDriver;

class Displayed extends \Magium\Assertions\Element\Displayed  implements SelectorAssertionInterface
{
    const ASSERTION = 'Text\Displayed';

    use TextTrait;

    public function assertSelector($selector)
    {
        $selector = $this->createXpath($selector);
        $this->setSelector($selector);
        $this->setBy(WebDriver::BY_XPATH);
        $this->assert();
    }

}
