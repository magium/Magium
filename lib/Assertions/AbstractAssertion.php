<?php

namespace Magium\Assertions;

use Magium\AbstractTestCase;
use Magium\InvalidTestTypeException;
use Magium\TestCaseAware;
use Magium\Util\Log\Logger;
use Magium\Util\Log\LoggerAware;
use Magium\WebDriver\WebDriver;
use Magium\WebDriver\WebDriverAware;

abstract class AbstractAssertion implements LoggerAware, TestCaseAware, WebDriverAware, AssertionInterface
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

    /**
     * @return AbstractTestCase|\PHPUnit_Framework_TestCase
     * @throws InvalidTestTypeException
     */

    protected function getTestCase()
    {
        if (!$this->testCase instanceof AbstractTestCase) {
            throw new InvalidTestTypeException('Magium only understands instances of Magium\AbstractTestCase');
        }
        return $this->testCase;
    }

}