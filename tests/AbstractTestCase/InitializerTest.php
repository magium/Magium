<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;
use Magium\TestCase\Initializer;
use Magium\Util\Api\Clairvoyant\Clairvoyant;

class InitializerTest extends \PHPUnit_Framework_TestCase
{

    public function testInitialize()
    {
        $initializer = new Initializer();
        $test = new JustMe(null, [], null, $initializer);
        $initializer->initialize($test);
        $webDriver = $test->get('Magium\WebDriver\WebDriver');
        self::assertInstanceOf('Magium\WebDriver\WebDriver', $webDriver);
    }

    public function testInitializeIsOnlyRunOnceWhenCalledTwice()
    {
        $clairvoyant = $this->getMockBuilder('Magium\Util\Api\Clairvoyant\Clairvoyant')->disableOriginalConstructor()->getMock();
        $builder = $this->getMockBuilder('Magium\TestCase\Initializer');
        $initializer = $builder->setMethods(['initClairvoyant'])->getMock();
        $initializer->expects(self::once())->method('initClairvoyant')->willReturn($clairvoyant);

        $test = new JustMe(null, [], null, $initializer);
        $initializer->initialize($test);
        $initializer->initialize($test);
    }

    public function testInitializeIsRunTwiceWhenForced()
    {
        $clairvoyant = $this->getMockBuilder('Magium\Util\Api\Clairvoyant\Clairvoyant')->disableOriginalConstructor()->getMock();
        $builder = $this->getMockBuilder('Magium\TestCase\Initializer');
        $initializer = $builder->setMethods(['initClairvoyant'])->getMock();
        $initializer->expects(self::exactly(2))->method('initClairvoyant')->willReturn($clairvoyant);

        $test = new JustMe(null, [], null, $initializer);
        $initializer->initialize($test);
        $initializer->initialize($test, true);
    }

}

eval(<<<CODE
namespace Tests\Magium\AbstractTestCase;

class JustMe extends \Magium\AbstractTestCase
{

    public function testIgnore()
    {
        // Just here for the previous test
    }
}
CODE
);