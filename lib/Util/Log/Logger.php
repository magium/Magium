<?php

namespace Magium\Util\Log;

use Exception;
use Magium\Assertions\AbstractAssertion;
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

    public function setMasterListener(MasterListener $listener)
    {
        $listener->addListener($this);
    }


    public function addCharacteristic($type, $value)
    {

    }

    public function executeAssertion(AbstractAssertion $assertion)
    {
        $assertion->assert();
        $this->addAssertionSuccess();
        // A failed assertion with throw an exception, which will be caught by addFailure()
    }

    public function setTestName($name)
    {
        $this->testName = $name;
    }

    public function setTestStatus($status)
    {
        $this->status = $status;
    }

    public function addAssertionSuccess()
    {

    }

    public function addAssertionFailure()
    {

    }

    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->setTestStatus(self::STATUS_FAILED);
    }

    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->setTestStatus(self::STATUS_FAILED);
    }

    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->setTestStatus(self::STATUS_INCOMPLETE);
    }

    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        // TODO: Implement addRiskyTest() method.
    }

    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        self::setTestStatus(self::STATUS_SKIPPED);
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
        $this->info(sprintf('Test %s completed with status: %s', $this->testName, $this->status));
        $this->testName = self::NAME_DEFAULT;
    }


}