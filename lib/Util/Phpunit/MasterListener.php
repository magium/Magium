<?php

namespace Magium\Util\Phpunit;

use Exception;
use Magium\Util\Log\Clairvoyant;
use Magium\Util\TestCase\RegistrationCallbackInterface;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_Test;
use PHPUnit_Framework_TestSuite;

class MasterListener implements \PHPUnit_Framework_TestListener
{

    protected $replay = [];
    protected $listeners = [];
    protected $result;

    /**
     * @param $class
     * @return \PHPUnit_Framework_TestListener
     */

    public function getListener($class)
    {
        foreach ($this->listeners as $listener) {
            if (get_class($listener) == $class) {
                return $listener;
            }
        }
    }

    public function clear()
    {
        $this->replay = [];
        $this->listeners = [];
        $this->result = null;
    }

    public function addListener(\PHPUnit_Framework_TestListener $listener)
    {
        foreach ($this->listeners as $existingListener) {
            if (get_class($listener) == get_class($existingListener)) {
                return;
            }
        }
        $this->listeners[] = $listener;
        foreach ($this->replay as $replay) {
            $this->play($replay['method'], $replay['args'], $listener);
        }
    }

    public function play($method, $args, \PHPUnit_Framework_TestListener $instance = null)
    {
        if ($instance instanceof \PHPUnit_Framework_TestListener) {
            call_user_func_array([$instance, $method], $args);
        } else {
            foreach ($this->listeners as $listener) {
                call_user_func_array([$listener, $method], $args);
            }
        }
    }

    public function bindToResult(\PHPUnit_Framework_TestResult $result)
    {
        if ($this->result !== $result) {
            $this->result = $result;
            $result->addListener($this);
        }
    }

    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function startTest(PHPUnit_Framework_Test $test)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        $this->replay[] = [
            'method' => __FUNCTION__,
            'args'  => func_get_args()
        ];
        $this->play(__FUNCTION__, func_get_args());
    }

}