<?php

namespace Magium\Assertions\Element;

class Exists extends AbstractSelectorAssertion
{

    const ASSERTION = 'Element\Exists';

    public function assert()
    {
        $this->getTestCase()->assertWebDriverElement($this->webDriver->{$this->by}($this->selector));

    }

}