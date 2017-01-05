<?php

namespace Magium\Assertions\Url;

use Magium\AbstractTestCase;
use Magium\Assertions\SelectorAssertionInterface;
use Magium\WebDriver\WebDriver;

abstract class AbstractUrlAssertion implements SelectorAssertionInterface
{

    protected $webDriver;
    protected $testCase;

    public function __construct(
        WebDriver $webDriver,
        AbstractTestCase $testCase
    )
    {
        $this->webDriver = $webDriver;
        $this->testCase = $testCase;
    }

}
