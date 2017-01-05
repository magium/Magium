<?php

namespace Magium\Assertions\Url;

use Magium\AbstractTestCase;

class Equals extends AbstractUrlAssertion
{

    const ASSERTION = 'Url\Equals';

    public function assertSelector($selector)
    {
        AbstractTestCase::assertEquals($selector, $this->webDriver->getCurrentURL());
    }

}
