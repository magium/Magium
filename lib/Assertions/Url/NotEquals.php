<?php

namespace Magium\Assertions\Url;

use Magium\AbstractTestCase;

class NotEquals extends AbstractUrlAssertion
{

    const ASSERTION = 'Url\NotEquals';

    public function assertSelector($selector)
    {
        AbstractTestCase::assertNotEquals($selector, $this->webDriver->getCurrentURL());
    }

}
