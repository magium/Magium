<?php

namespace Magium\Util\Log;

use Exception;
use League\OAuth1\Client\Credentials\ClientCredentials;
use League\OAuth1\Client\Server\Magento;
use Magium\AbstractTestCase;
use Magium\Util\Api\ApiConfiguration;
use Magium\Util\Api\Request;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_Test;
use PHPUnit_Framework_TestSuite;
use Zend\Log\Filter\FilterInterface as Filter;
use Zend\Log\Formatter\FormatterInterface as Formatter;
use Zend\Log\Writer\WriterInterface;

class Clairvoyant implements WriterInterface, \PHPUnit_Framework_TestListener
{

    const TYPE_TEST_RESULT = 'test-result';
    const TYPE_TEST_STATUS = 'test-status';
    const TYPE_TEST_CHECKPOINT = 'test-checkpoint';

    const TEST_RESULT_ERROR = 'error';
    const TEST_RESULT_FAILED = 'failed';
    const TEST_RESULT_SKIPPED = 'skipped';
    const TEST_RESULT_RISKY = 'risky';
    const TEST_RESULT_INCOMPLETE = 'incomplete';

    const TEST_STATUS_STARTED = 'started';
    const TEST_STATUS_COMPLETED = 'completed';


    protected $testName;
    protected $testTitle;
    protected $testDescription;
    protected $request;
    protected $events = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function reset()
    {
        $this->testDescription
            = $this->testName
            = $this->testTitle = null;
        $this->events = [];
    }

    public function markKeyCheckpoint($checkpoint)
    {
        $this->write([
            'message'   => $checkpoint,
            'extra'     => [
                'type'      => self::TYPE_TEST_CHECKPOINT,
                'value'    => $checkpoint
            ]
        ]);
    }

    /**
     * @param mixed $testDescription
     */
    public function setTestDescription($testDescription)
    {
        $this->testDescription = $testDescription;
    }

    /**
     * @param mixed $testTitle
     */
    public function setTestTitle($testTitle)
    {
        $this->testTitle = $testTitle;
    }

    public function addFilter($filter)
    {
        // Ignore - The filter is on the server side
    }

    public function setFormatter($formatter)
    {
        // Ignore
    }

    public function write(array $event)
    {
        $event['microtime'] = microtime(true);
        $this->events[] = $event;
    }

    public function shutdown()
    {
        $a = 1;
    }

    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->write([
            'message'   => $e->getMessage(),
            'extra'     => [
                'type'      => self::TYPE_TEST_RESULT,
                'value'    => self::TEST_RESULT_ERROR
            ]
        ]);
    }

    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->write([
            'message'   => $e->getMessage(),
            'extra'     => [
                'type'      => self::TYPE_TEST_RESULT,
                'value'    => self::TEST_RESULT_FAILED
            ]
        ]);
    }

    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->write([
            'message'   => $e->getMessage(),
            'extra'     => [
                'type'      => self::TYPE_TEST_RESULT,
                'value'    => self::TEST_RESULT_INCOMPLETE
            ]
        ]);
    }

    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->write([
            'message'   => $e->getMessage(),
            'extra'     => [
                'type'      => self::TYPE_TEST_RESULT,
                'value'    => self::TEST_RESULT_RISKY
            ]
        ]);
    }

    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->write([
            'message'   => $e->getMessage(),
            'extra'     => [
                'type'      => self::TYPE_TEST_RESULT,
                'value'    => self::TEST_RESULT_SKIPPED
            ]
        ]);
    }

    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        $this->reset();
    }

    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        $this->send();
    }

    public function startTest(PHPUnit_Framework_Test $test)
    {
        $this->reset();
        if ($test instanceof AbstractTestCase) {
            $this->testName = $test->getName();
        }
        $this->write([
            'message'   => 'Test started',
            'extra'     => [
                'type'      => self::TYPE_TEST_STATUS,
                'value'    => self::TEST_STATUS_STARTED,
                'class'     => get_class($test),
                'name'      => $test->getName()
            ]
        ]);
    }

    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        $this->write([
            'message'   => 'Test completed',
            'extra'     => [
                'type'      => self::TYPE_TEST_STATUS,
                'value'    => self::TEST_STATUS_COMPLETED
            ]
        ]);
        $this->send();
    }

    public function send()
    {
        $this->request->push('/clairvoyant/api/ingest', $this->events);
    }
}