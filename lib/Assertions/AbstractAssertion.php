<?php

namespace Magium\Assertions;

use Magium\TestCaseAware;
use Magium\Util\Log\Logger;
use Magium\Util\Log\LoggerAware;
use Magium\WebDriver\WebDriver;
use Magium\WebDriver\WebDriverAware;

abstract class AbstractAssertion implements LoggerAware, TestCaseAware, WebDriverAware
{
    /**
     * @var Logger
     */

    protected $logger;

    /**
     * @var \PHPUnit_Framework_TestCase
     */

    protected $testCase;

    /**
     * @var WebDriver
     */

    protected $webDriver;

    public function setWebDriver(WebDriver $webdriver)
    {
        $this->webDriver = $webdriver;
    }

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function setTestCase(\PHPUnit_Framework_TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public abstract function assert();
}