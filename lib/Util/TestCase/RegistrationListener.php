<?php

namespace Magium\Util\TestCase;

use Magium\AbstractTestCase;
use Zend\Stdlib\SplPriorityQueue;

class RegistrationListener
{
    protected static $callbacks;
    protected static $currentTest;

    /**
     * @return SplPriorityQueue
     */

    public static function getCallbacks()
    {
        if (!self::$callbacks instanceof SplPriorityQueue) {
            self::$callbacks = new SplPriorityQueue();
        }
        return self::$callbacks;
    }

    public static function executeCallbacks(AbstractTestCase $test)
    {
        foreach (self::getCallbacks() as $callback) {
            /* @var $callback \Magium\Util\TestCase\RegistrationCallbackInterface */
            $callback->register($test);
        }
    }

    public static function addCallback(RegistrationCallbackInterface $callback, $priority = 0)
    {
        self::getCallbacks()->insert($callback, $priority);
    }

}