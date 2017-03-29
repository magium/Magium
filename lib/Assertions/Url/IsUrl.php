<?php

namespace Magium\Assertions\Url;

use Magium\AbstractTestCase;

class IsUrl extends AbstractUrlAssertion
{

    public function assertSelector($selector)
    {
        $result = parse_url($selector);
        AbstractTestCase::assertNotFalse($result);
    }

}
