<?php

namespace Magium\Assertions\Url;

use Magium\AbstractTestCase;

class Contains extends AbstractUrlAssertion
{

    const ASSERTION = 'Url\Contains';

    public function assertSelector($selector)
    {
        AbstractTestCase::assertContains($selector, $this->webDriver->getCurrentURL());
    }

}
