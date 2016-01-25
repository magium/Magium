<?php

namespace Magium\Assertions;

use Magium\TestCaseAware;
use Magium\Util\Log\Logger;
use Magium\Util\Log\LoggerAware;

abstract class AbstractAssertion implements LoggerAware, TestCaseAware
{
    /**
     * @var Logger
     */

    protected $logger;

    /**
     * @var \PHPUnit_Framework_TestCase
     */

    protected $testCase;
    protected $lastXpath;

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function setTestCase(\PHPUnit_Framework_TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function getLastXpath()
    {
        return $this->lastXpath;
    }

    public abstract function assert();
}