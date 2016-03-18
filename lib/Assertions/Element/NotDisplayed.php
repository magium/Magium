<?php

namespace Magium\Assertions\Element;


class NotDisplayed extends AbstractSelectorAssertion
{

    const ASSERTION = 'Element\NotDisplayed';

    public function assert()
    {

        try {
            $this->getTestCase()->assertElementExists($this->selector, $this->by);
            $this->getTestCase()->assertTrue(
                $this->webDriver->{$this->by}($this->selector)->isDisplayed(),
                sprintf('The element: %s, is not displayed and it should have been', $this->selector)
            );
        } catch (\Exception $e) {
            $this->getTestCase()->fail(sprintf('Element "%s" cannot be found using selector "%s"', $this->selector, $this->by));
        }
    }

}