<?php

namespace Magium\Util\Phpunit;

use Exception;
use Magium\Util\TestCase\RegistrationCallbackInterface;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_Test;
use PHPUnit_Framework_TestSuite;

class MasterListener implements \PHPUnit_Framework_TestListener
{

    protected static $replay = [

    ];
    protected static $listeners = [];

    public static function addListener(\PHPUnit_Framework_TestListener $listener)
    {
        foreach (self::$listeners as $existingListener) {
            if ($listener === $existingListener) {
                return;
            }
        }
        self::$listeners[] = $listener;
        foreach (self::$replay as $replay) {
            self::play($replay['method'], $replay['args'], $listener);
        }
    }

    public static function play($method, $args, \PHPUnit_Framework_TestListener $instance = null)
    {
        if ($instance instanceof \PHPUnit_Framework_TestListener) {
            call_user_func_array([$instance, $method], $args);
        } else {
            foreach (self::$listeners as $listener) {
                call_user_func_array([$listener, $method], $args);
            }
        }
    }

    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        self::$replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        self::play(__FUNCTION__, func_get_args());
    }

    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        self::$replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        self::play(__FUNCTION__, func_get_args());
    }

    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        self::$replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        self::play(__FUNCTION__, func_get_args());
    }

    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        self::$replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        self::play(__FUNCTION__, func_get_args());
    }

    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        self::$replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        self::play(__FUNCTION__, func_get_args());
    }

    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        self::$replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        self::play(__FUNCTION__, func_get_args());
    }

    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        self::$replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        self::play(__FUNCTION__, func_get_args());
    }

    public function startTest(PHPUnit_Framework_Test $test)
    {
        self::$replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        self::play(__FUNCTION__, func_get_args());
    }

    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        self::$replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        self::play(__FUNCTION__, func_get_args());
    }

}