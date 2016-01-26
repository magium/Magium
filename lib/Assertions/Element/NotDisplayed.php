<?php

namespace Magium\Assertions\Element;


class NotDisplayed extends AbstractSelectorAssertion
{

    const ASSERTION = 'Element\NotDisplayed';

    public function assert()
    {

        try {
            $this->testCase->assertElementExists($this->selector, $$this->by);
            $this->testCase->assertTrue(
                $this->webdriver->{$this->by}($this->selector)->isDisplayed(),
                sprintf('The element: %s, is not displayed and it should have been', $this->selector)
            );
        } catch (\Exception $e) {
            $this->testCase->fail(sprintf('Element "%s" cannot be found using selector "%s"', $this->selector, $this->by));
        }
    }

}