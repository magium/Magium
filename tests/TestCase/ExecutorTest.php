<?php

namespace Tests\Magium\TestCase;

use Magium\AbstractTestCase;
use Magium\TestCase\Executor;

class ExecutorTest extends AbstractTestCase
{

    /**
     *
     * @return Executor
     */

    protected function getExecutor()
    {
        $executor = $this->get(Executor::class);
        self::assertInstanceOf(Executor::class, $executor);
        return $executor;
    }

    public function testEvaluateBooleanTrue()
    {
        $result = $this->getExecutor()->evaluate('true');
        self::assertTrue($result);
    }

    public function testEvaluateBooleanFalse()
    {
        $result = $this->getExecutor()->evaluate('false');
        self::assertFalse($result);
    }

    public function testEvaluateStringTrue()
    {
        $result = $this->getExecutor()->evaluate('some string');
        self::assertTrue($result);
    }

    public function testEvaluateNullFalse()
    {
        $result = $this->getExecutor()->evaluate('null');
        self::assertFalse($result);
    }

    public function testEvaluateEmptyFalse()
    {
        $result = $this->getExecutor()->evaluate('');
        self::assertFalse($result);
    }

    public function testEquals()
    {
        $result = $this->getExecutor()->evaluate('something == something');
        self::assertTrue($result);
    }

    public function testEqualsWithPadding()
    {
        $result = $this->getExecutor()->evaluate('  something  == something');
        self::assertTrue($result);
    }

    public function testNotEquals()
    {
        $result = $this->getExecutor()->evaluate('something != something else');
        self::assertFalse($result);
    }

    public function testGreaterThan()
    {
        $result = $this->getExecutor()->evaluate('1 > 0');
        self::assertTrue($result);
    }

    public function testGreaterOrEqualThan()
    {
        $result = $this->getExecutor()->evaluate('1 >= 1');
        self::assertTrue($result);
    }

    public function testLessThan()
    {
        $result = $this->getExecutor()->evaluate('0 < 1');
        self::assertTrue($result);
    }

    public function testLessThanOrEqual()
    {
        $result = $this->getExecutor()->evaluate('0 <= 0');
        self::assertTrue($result);
    }

}
