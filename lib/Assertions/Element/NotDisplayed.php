<?php

namespace Magium\Assertions\Element;


use Magium\AbstractTestCase;

class NotDisplayed extends AbstractSelectorAssertion
{

    const ASSERTION = 'Element\NotDisplayed';

    public function assert()
    {
        $this->getTestCase()->assertElementExists($this->selector, $this->by);
        AbstractTestCase::assertFalse(
            $this->webDriver->{$this->by}($this->selector)->isDisplayed(),
            sprintf('The element: %s, is displayed and it should not have been', $this->selector)
        );
    }

}
