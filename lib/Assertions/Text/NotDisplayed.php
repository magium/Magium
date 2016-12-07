<?php

namespace Magium\Assertions\Text;

use Magium\Assertions\SelectorAssertionInterface;
use Magium\Assertions\Text\TextTrait;
use Magium\WebDriver\WebDriver;

class NotDisplayed extends \Magium\Assertions\Element\NotDisplayed  implements SelectorAssertionInterface
{
    const ASSERTION = 'Text\NotDisplayed';

    use TextTrait;

    public function assertSelector($selector)
    {
        $selector = $this->createXpath($selector);
        $this->setSelector($selector);
        $this->setBy(WebDriver::BY_XPATH);
        $this->assert();
    }

}
