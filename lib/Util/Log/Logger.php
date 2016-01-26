<?php

namespace Magium\Util\Log;

use Exception;
use Magium\Assertions\AbstractAssertion;
use Magium\Assertions\AbstractSelectorAssertion;
use Magium\Util\Phpunit\MasterListener;
use Magium\Util\Phpunit\MasterListenerAware;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_Test;
use PHPUnit_Framework_TestSuite;

class Logger extends \Zend\Log\Logger implements \PHPUnit_Framework_TestListener, MasterListenerAware
{

    const CHARACTERISTIC_BROWSER = 'browser';
    const CHARACTERISTIC_OPERATING_SYSTEM = 'operating-system';
    const CHARACTERISTIC_THEME = 'theme';

    const STATUS_PASSED = 'passed';
    const STATUS_FAILED = 'failed';
    const STATUS_SKIPPED = 'skipped';
    const STATUS_INCOMPLETE = 'incomplete';

    const NAME_DEFAULT = 'unknown';

    protected $status = self::STATUS_PASSED;

    protected $testName = self::NAME_DEFAULT;

    protected $testId;

    protected $selectorConfig = null;

    public function setMasterListener(MasterListener $listener)
    {
        $listener->addListener($this);
    }


    public function addCharacteristic($type, $value)
    {

    }


    public function setTestName($name)
    {
        $this->testName = $name;
    }

    public function setTestStatus($status)
    {
        $this->status = $status;
    }

    public function setTestId($testId)
    {
        $this->testId = $testId;
    }

    public function logStep($stepId)
    {
        $this->log($stepId, $this->createExtra(['type' => 'step']));
    }

    public function logAssertionSuccess(AbstractAssertion $assertion, array $extra)
    {
        $extra = array_merge($extra, ['type' => 'assertion', 'result' => self::STATUS_PASSED]);
        $this->info(get_class($assertion), $this->createExtra($extra));
    }

    public function logAssertionFailure(AbstractAssertion $assertion, array $extra)
    {
        $extra = array_merge($extra, ['type' => 'assertion', 'result' => self::STATUS_FAILED]);
        $this->info(get_class($assertion), $this->createExtra($extra));
    }

    public function createExtra($includeArray = [])
    {
        $defaultArray = [
            'type'      => 'message',
            'status'    => $this->status,
            'name'     => $this->testName,
            'testId'    => $this->testId
        ];

        if ($this->selectorConfig) {
            $defaultArray = array_merge($this->selectorConfig, $defaultArray);
        }

        return array_merge($defaultArray, $includeArray);
    }

    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->setTestStatus(self::STATUS_FAILED);
        $this->notice($e->getMessage(), $this->createExtra(['trace' => $e->getTraceAsString()]));
    }

    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->setTestStatus(self::STATUS_FAILED);
        $this->notice($e->getMessage(), $this->createExtra(['trace' => $e->getTraceAsString()]));
    }

    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->setTestStatus(self::STATUS_INCOMPLETE);
        $this->notice($e->getMessage(), $this->createExtra());
    }

    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // TODO: Implement addRiskyTest() method.
    }

    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        self::setTestStatus(self::STATUS_SKIPPED);
        $this->notice($e->getMessage(), $this->createExtra());
    }

    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        // TODO: Implement startTestSuite() method.
    }

    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        $this->testName = self::NAME_DEFAULT;
    }

    public function startTest(PHPUnit_Framework_Test $test)
    {
        if ($test instanceof \PHPUnit_Framework_TestCase) {
            $this->setTestName(get_class($test) . '::' . $test->getName());
            $this->setTestStatus(self::STATUS_PASSED);
        }

    }

    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        $this->info(sprintf('Test completed with status: %s', $this->status), $this->createExtra());
        $this->testName = self::NAME_DEFAULT;
    }


}