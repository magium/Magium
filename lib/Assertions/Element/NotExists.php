<?php

namespace Magium\Assertions\Element;


class NotExists extends AbstractSelectorAssertion
{

    const ASSERTION = 'Element\NotExists';

    public function assert()
    {

        try {
            $this->testCase->assertWebDriverElement($this->webDriver->{$this->by}($this->selector));
            $this->fail(sprintf('Element "%s" was found using selector "%s"', $this->selector, $this->by));
        } catch (\Exception $e) {
        }
    }

}