<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;
use Magium\Util\TestCase\RegistrationCallbackInterface;

class PriorityRegistrationTest extends AbstractTestCase
{

    protected $p1;
    protected $p2;

    protected function setUp()
    {
        $this->p1 = new Priority();
        $this->p2 = new Priority();
        self::addRegistrationCallback($this->p1, 10);
        self::addRegistrationCallback($this->p2, 0);
        parent::setUp();
    }

    public function testPriority()
    {
        self::assertGreaterThan($this->p1, $this->p2);
    }


}

abstract class AbstractRegistrationCallback implements RegistrationCallbackInterface
{

    public $executedAt;

    public function register(AbstractTestCase $testCase)
    {
        $this->executedAt = microtime(true);
    }
}

class Priority extends SecondAbstractRegistrationCallback
{
}