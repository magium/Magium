<?php

namespace Magium\Assertions\Url;

use Magium\AbstractTestCase;

class IsUrl extends AbstractUrlAssertion
{

    const ASSERTION = 'Url\IsUrl';

    public function assertSelector($selector)
    {
        $result = parse_url($selector);
        AbstractTestCase::assertInternalType('array', $result);
        AbstractTestCase::assertArrayHasKey('scheme', $result);
        AbstractTestCase::assertArrayHasKey('host', $result);
        AbstractTestCase::assertArrayHasKey('path', $result);
    }

}
