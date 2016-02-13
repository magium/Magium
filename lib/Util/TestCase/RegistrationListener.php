<?php

namespace Magium\Util\TestCase;

use Magium\AbstractTestCase;
use Zend\Stdlib\SplPriorityQueue;

class RegistrationListener
{
    protected static $callbacks;

    public static function executeCallbacks(AbstractTestCase $testCase)
    {
        $callbacks = self::getCallbacks();
        self::$callbacks = new SplPriorityQueue();
        foreach ($callbacks as $callback) {
            list($callback, $priority) = $callback;
            if ($callback instanceof RegistrationCallbackInterface) {
                $callback->register($testCase);
            }
            self::addCallback($callback, $priority);
        }
    }

    /**
     * @return SplPriorityQueue
     */

    protected static function getCallbacks()
    {
        if (!self::$callbacks instanceof SplPriorityQueue) {
            self::$callbacks = new SplPriorityQueue();
        }
        return self::$callbacks;
    }

    public static function addCallback(RegistrationCallbackInterface $callback, $priority = 0)
    {
        self::getCallbacks()->insert([$callback, $priority], $priority);
    }

}
