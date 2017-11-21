<?php

namespace Magium\Assertions\Element;


use Magium\AbstractTestCase;

class Displayed extends AbstractSelectorAssertion
{

    const ASSERTION = 'Element\Displayed';

    public function assert()
    {
        $this->getTestCase()->assertElementExists($this->selector, $this->by);
        AbstractTestCase::assertTrue(
            $this->webDriver->{$this->by}($this->selector)->isDisplayed(),
            sprintf('The element: %s, is not displayed and it should have been', $this->selector)
        );

    }

}
