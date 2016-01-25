<?php

namespace Tests\Magium\Util;

use Magium\AbstractTestCase;
use Magium\Assertions\AbstractAssertion;
use Zend\Log\Writer\Noop;
use Zend\Stdlib\SplPriorityQueue;

class LoggerTest extends AbstractTestCase
{
    public function testInstance()
    {
        self::assertInstanceOf('Magium\Util\Log\Logger', $this->getLogger());
    }

    public function testSuccessfulAssertionLogged()
    {
        $logger = $this->getMockBuilder('Magium\Util\Log\Logger')->disableOriginalConstructor()->setMethods(['addAssertionSuccess'])->getMock();
        /* @var $logger1 \Magium\Util\Log\Logger */
        $logger->setWriters(new SplPriorityQueue());
        $logger->addWriter(new Noop());

        $logger->expects($this->once())->method('addAssertionSuccess');

        $this->di->instanceManager()->addSharedInstance($logger, 'Magium\Util\Log\Logger');
        $positiveAssertion = $this->get('Tests\Magium\Util\PositiveAssertion');
        $this->getLogger()->executeAssertion($positiveAssertion);
    }

}

class PositiveAssertion extends AbstractAssertion
{

    public function assert()
    {
        $this->testCase->assertTrue(true);
    }

}

class NegativeAssertion extends AbstractAssertion
{

    public function assert()
    {
        $this->testCase->assertFalse(true);
    }

}