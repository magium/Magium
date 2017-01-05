<?php

namespace Magium\Assertions\Url;

use Magium\AbstractTestCase;

class NotContains extends AbstractUrlAssertion
{

    const ASSERTION = 'Url\NotContains';

    public function assertSelector($selector)
    {
        AbstractTestCase::assertNotContains($selector, $this->webDriver->getCurrentURL());
    }

}
