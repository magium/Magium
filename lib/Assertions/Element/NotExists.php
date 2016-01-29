<?php

namespace Magium\Assertions\Element;


class NotExists extends AbstractSelectorAssertion
{

    const ASSERTION = 'Element\NotExists';

    public function assert()
    {
        $exceptionThrown = false;
        try {
            $this->testCase->assertWebDriverElement($this->webDriver->{$this->by}($this->selector));
        } catch (\Exception $e) {
            $exceptionThrown = true;
        }
        if (!$exceptionThrown) {
            $this->testCase->fail(sprintf('Element "%s" was found using selector "%s"', $this->selector, $this->by));
        }
    }

}