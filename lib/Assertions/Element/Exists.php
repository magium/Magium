<?php

namespace Magium\Assertions\Element;


class Exists extends AbstractSelectorAssertion
{

    const ASSERTION = 'Element\Exists';

    public function assert()
    {
        $this->testCase->assertWebDriverElement($this->webDriver->{$this->by}($this->selector));

    }

}