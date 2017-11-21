<?php

namespace Magium;


use PHPUnit\Framework\TestCase;

interface TestCaseAware
{
    public function setTestCase(TestCase $testCase);
}
