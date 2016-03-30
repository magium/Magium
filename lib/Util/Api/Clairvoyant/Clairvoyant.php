<?php

namespace Magium\Util\Api\Clairvoyant;

use Exception;
use Magium\AbstractConfigurableElement;
use Magium\AbstractTestCase;
use Magium\Util\Api\ApiConfiguration;
use Magium\Util\Api\Request;
use Magium\Util\Configuration\ConfigurationCollector\DefaultPropertyCollector;
use Magium\Util\Configuration\ConfigurationProviderInterface;
use Magium\Util\Configuration\StandardConfigurationProvider;
use Magium\Util\Log\Logger;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_Test;
use PHPUnit_Framework_TestSuite;
use RandomLib\Factory;
use SecurityLib\Strength;
use Zend\Log\Writer\WriterInterface;

class Clairvoyant extends AbstractConfigurableElement implements WriterInterface, \PHPUnit_Framework_TestListener
{
    const TYPE_TEST_RESULT = 'test-result';
    const TYPE_TEST_STATUS = 'test-status';
    const TYPE_TEST_CHECKPOINT = 'test-checkpoint';

    const TEST_RESULT_PASSED = 'passed';
    const TEST_RESULT_ERROR = 'error';
    const TEST_RESULT_FAILED = 'failed';
    const TEST_RESULT_SKIPPED = 'skipped';
    const TEST_RESULT_RISKY = 'risky';
    const TEST_RESULT_INCOMPLETE = 'incomplete';

    const TEST_STATUS_STARTED = 'started';
    const TEST_STATUS_COMPLETED = 'completed';

    /**
     * This provides the Clairvoyant-based project ID.  It must be retrieved from the MagiumLib.com website.  If 
     * Clairvoyant is enabled and this project ID is missing an exception will be thrown.
     *
     * @var string
     */

    public $projectId;
    public $enabled = null;

    protected $testName;
    protected $logger;
    protected $testTitle;
    protected $testDescription;
    protected $capability;
    protected $sessionId;
    /**
     * @var Request
     */
    protected $request;
    protected $testId;
    protected static $testRunId;
    protected $events = [];
    protected $apiConfiguration;
    protected $testResult;
    protected $characteristics = [];

    public function __construct(
        ConfigurationProviderInterface $configurationProvider,
        DefaultPropertyCollector $collector,
        ApiConfiguration $apiConfiguration,
        Logger $logger)
    {
        parent::__construct($configurationProvider, $collector);
        $this->apiConfiguration = $apiConfiguration;
        $this->logger = $logger;
    }


    /**
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * @param mixed $projectId
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;
    }



    public function setApiRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Provide a test-run-wide ID that can be used to tie individual test runs together
     *
     * @param string $id
     */

    public static function setTestRunId($id = null)
    {
        if ($id === null) {

            $factory = new Factory();
            $generator = $factory->getGenerator(new Strength(Strength::MEDIUM));
            $id = $generator->generateString(64);
        }
        self::$testRunId = $id;
    }

    public function reset()
    {
        $this->testDescription
            = $this->testName
            = $this->testTitle = null;
        $this->testResult = self::TEST_RESULT_PASSED;
        $this->characteristics = [];
        $this->events = [];

        $factory = new Factory();
        $generator = $factory->getGenerator(new Strength(Strength::MEDIUM));
        $this->testId = $generator->generateString(64);
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

    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }

    /**
     * @param mixed $capability
     */
    public function setCapability($capability)
    {
        $this->capability = $capability;
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
        if (isset($event['extra']['type']) && $event['extra']['type'] == 'characteristic') {
            $this->characteristics[$event['extra']['characteristic']] = $event['extra']['value'];
            return;
        }
        if (isset($event['extra'][self::TYPE_TEST_RESULT])) {
            $this->testResult = $event['extra'][self::TYPE_TEST_RESULT];
        }
        $event['microtime'] = microtime(true);
        $this->events[] = $event;
    }

    public function shutdown()
    {
        $this->send(); // Final try, just in case (this should never actually send data)
    }

    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->testResult = self::TEST_RESULT_ERROR;
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
        $this->testResult = self::TEST_RESULT_FAILED;
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
        $this->testResult = self::TEST_RESULT_INCOMPLETE;
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
        $this->testResult = self::TEST_RESULT_RISKY;
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
        $this->testResult = self::TEST_RESULT_SKIPPED;
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
        $this->send(); // Just in case.
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
                'name'      => $this->testName
            ]
        ]);
    }

    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        $this->write([
            'message'   => 'Test completed',
            'extra'     => [
                'type'      => self::TYPE_TEST_STATUS,
                'value'    => self::TEST_STATUS_COMPLETED,
            ]
        ]);
        $this->send();
    }

    public function send()
    {

        if (!$this->enabled === null) {
            $this->enabled = $this->apiConfiguration->getEnabled();
        }

        if (!empty($this->events) && $this->enabled) {
            if (!$this->getProjectId()) {
                throw new MissingProjectIdException('Missing the project ID.  You either need to disable Clairvoyant or get a project ID from http://magiumlib.com/');
            }

            $this->write([
                'message'   => 'Final test result',
                'extra'     => [
                    'type'      => self::TYPE_TEST_RESULT,
                    'value'    => $this->testResult,
                ]
            ]);
            $payload = [
                'title'             => $this->testTitle,
                'description'       => $this->testDescription,
                'id'                => $this->testId,
                'session_id'        => $this->sessionId,
                'events'            => $this->events,
                'version'           => '1',
                'project_id'        => $this->projectId,
                'invoked_test'      => $this->logger->getInvokedTest(),
                'characteristics'   => $this->characteristics
            ];

            if (self::$testRunId !== null) {
                $payload['test_run_id'] = self::$testRunId;
            }
            $this->request->push('/clairvoyant/api/ingest', $payload);
        }
        $this->events = [];
    }

}