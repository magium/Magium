<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class AbstractTestCaseRegistrationCallback implements \Magium\Util\TestCase\RegistrationCallbackInterface
{
    public static $callCount = 0;
    protected $internalCallCount = 0;

    public function register(\Magium\AbstractTestCase $testCase)
    {
        $this->internalCallCount++;
        self::$callCount = $this->internalCallCount;
    }

}

\Magium\Util\TestCase\RegistrationListener::addCallback(new AbstractTestCaseRegistrationCallback());