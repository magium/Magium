<?php

namespace Magium;


interface TestCaseAware
{
    public function setTestCase(\PHPUnit_Framework_TestCase $testCase);
}