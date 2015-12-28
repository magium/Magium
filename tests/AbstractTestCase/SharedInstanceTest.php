<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;

class ExtendedTestCase extends AbstractTestCase {}

class SharedInstanceTest extends ExtendedTestCase
{

    public function testInjections()
    {
        self::assertTrue($this->get('Tests\Magium\AbstractTestCase\ClassWithAbstractTestCaseDependency')->getTestCase() instanceof SharedInstanceTest);
        self::assertTrue($this->get('Tests\Magium\AbstractTestCase\ClassWithExtendedTestCaseDependency')->getTestCase() instanceof SharedInstanceTest);
        self::assertTrue($this->get('Tests\Magium\AbstractTestCase\ClassWithSharedInstanceTestDependency')->getTestCase() instanceof SharedInstanceTest);
    }

}

interface TestCaseInterface
{
    public function getTestCase();
}

class ClassWithAbstractTestCaseDependency implements TestCaseInterface
{

    protected $testCase;

    public function __construct(
        AbstractTestCase $testCase
    )
    {
        $this->testCase = $testCase;
    }

    public function getTestCase()
    {
        return $this->testCase;
    }

}

class ClassWithExtendedTestCaseDependency implements TestCaseInterface
{

    protected $testCase;

    public function __construct(
        ExtendedTestCase $testCase
    )
    {
        $this->testCase = $testCase;
    }

    public function getTestCase()
    {
        return $this->testCase;
    }

}


class ClassWithSharedInstanceTestDependency implements TestCaseInterface
{

    protected $testCase;

    public function __construct(
        SharedInstanceTest $testCase
    )
    {
        $this->testCase = $testCase;
    }

    public function getTestCase()
    {
        return $this->testCase;
    }

}