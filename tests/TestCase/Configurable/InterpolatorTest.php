<?php

namespace Tests\Magium\TestCase\Configurable;

use Magium\AbstractTestCase;
use Magium\TestCase\Configurable\Instruction;
use Magium\TestCase\Configurable\InstructionInterface;
use Magium\TestCase\Configurable\InstructionsCollection;
use Magium\TestCase\Configurable\Interpolator;
use Magium\TestCase\Configurable\InvalidInstructionException;

class InterpolatorTest extends AbstractTestCase
{

    /**
     * @return Interpolator
     */

    protected function getInterpolator()
    {
        $this->getDi()->instanceManager()->addAlias('executeMe', ExecuteMe::class);
        $interpolator = $this->get(Interpolator::class);
        self::assertInstanceOf(Interpolator::class, $interpolator);
        return $interpolator;
    }

    public function testNullResultThrowsException()
    {
        $this->setExpectedException(InvalidInstructionException::class);
        $interpolator = $this->getInterpolator();
        $interpolator->interpolate('set {{$executeMe->noParams()->boogers()}}');
    }

    public function testResultInterpolatedWithString()
    {

        $interpolator = $this->getInterpolator();
        $result = $interpolator->interpolate('set {{$executeMe}}');
        $test = (string)$this->get('executeMe');
        $test = sprintf('set %s', (string)$test);
        self::assertEquals($test, $result);
    }

    public function testResultInterpolatedWithOneMethodCall()
    {
        $interpolator = $this->getInterpolator();
        $result = $interpolator->interpolate('set {{$executeMe->toString()}}');
        $test = (string)$this->get('executeMe');
        $test = sprintf('set %s', (string)$test);
        self::assertEquals($test, $result);

    }

    public function testInterpolatedWithParam()
    {
        $interpolator = $this->getInterpolator();
        $this->get('executeMe')->withParam = 5;

        $result = $interpolator->interpolate('set {{$executeMe->withParam}}');

        // withParam returns the value that is passed to it
        self::assertEquals('set 5', $result);
    }

    public function testResultInterpolatedWithOneMethodCallAndOneParam()
    {
        $interpolator = $this->getInterpolator();
        $result = $interpolator->interpolate('set {{$executeMe->withParam( 5 )}}');

        // withParam returns the value that is passed to it
        self::assertEquals('set 5', $result);

    }

    public function testResultInterpolatedWithOneMethodCallAndTwoParams()
    {
        $interpolator = $this->getInterpolator();
        $result = $interpolator->interpolate('set {{$executeMe->multiply(2, 3)}}');

        $test = sprintf('set 6');
        self::assertEquals($test, $result);
    }

    public function testResultInterpolatedWithRecursion()
    {
        $interpolator = $this->getInterpolator();

        $result = $interpolator->interpolate('set {{$executeMe->getMe()->toString()}}');
        $test = (string)$this->get('executeMe');
        $test = sprintf('set %s', (string)$test);
        self::assertEquals($test, $result);

    }

    public function testResultInterpolatedWithRecursionAndParameters()
    {
        $interpolator = $this->getInterpolator();
        $result = $interpolator->interpolate('set {{$executeMe->getMe()->multiply(2, 3)}}');

        $test = sprintf('set 6');
        self::assertEquals($test, $result);
    }
}
