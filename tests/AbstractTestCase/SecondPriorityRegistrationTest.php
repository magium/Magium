<?php

namespace Tests\Magium\AbstractTestCase;

require_once 'priority.bootstrap.php';

use Magium\AbstractTestCase;
use Magium\Util\TestCase\RegistrationCallbackInterface;
use Magium\Util\TestCase\RegistrationListener;

class SecondPriorityRegistrationTest extends AbstractTestCase
{

    protected $p1;
    protected $p2;

    protected function setUp()
    {
        $this->p1 = new SecondPriority();
        $this->p2 = new SecondPriority();

        // Note.  Registration callbacks should not be set in a test case.  They should be set in a module's
        // require_once or composer autoload.

        RegistrationListener::addCallback($this->p2, 0);
        RegistrationListener::addCallback($this->p1, 10);
        parent::setUp();
    }

    public function testPriority()
    {
        self::assertGreaterThan($this->p1, $this->p2);

    }

    public function testPriorityBoostrapCalledMoreThanOnce()
    {
        self::assertGreaterThan(1, \AbstractTestCaseRegistrationCallback::$callCount);
    }

}

abstract class SecondAbstractRegistrationCallback implements RegistrationCallbackInterface
{

    public $executedAt;

    public function register(AbstractTestCase $testCase)
    {
        $this->executedAt = microtime(true);
    }
}

class SecondPriority extends SecondAbstractRegistrationCallback
{
}