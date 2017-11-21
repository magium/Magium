<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\TestCase\Initializer;
use PHPUnit\Framework\TestCase;

class InitializerTest extends TestCase
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
        $builder = $this->getMockBuilder(Initializer::class);
        $initializer = $builder->setMethods(['attachMasterListener'])->getMock();
        $initializer->expects(self::once())->method('attachMasterListener');

        $test = new JustMe(null, [], null, $initializer);
        $initializer->initialize($test);
        $initializer->initialize($test);
    }

    public function testInitializeIsRunTwiceWhenForced()
    {
        $builder = $this->getMockBuilder(Initializer::class);
        $initializer = $builder->setMethods(['attachMasterListener'])->getMock();
        $initializer->expects(self::exactly(2))->method('attachMasterListener');

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
