<?php

namespace Tests\Magium\TestCase\Configurable;

use Magium\AbstractTestCase;
use Magium\TestCase\Configurable\Instruction;
use Magium\TestCase\Configurable\InstructionInterface;
use Magium\TestCase\Configurable\InstructionsCollection;
use Magium\TestCase\Configurable\InvalidInstructionException;

class ConfigurableTestTest extends AbstractTestCase
{

    protected function getCollection()
    {
        return new InstructionsCollection($this->getDi());
    }

    public function testCollectionAddsInstruction()
    {
        $instructionMock = $this->getMock(InstructionInterface::class);
        $this->getCollection()->addInstruction($instructionMock);
    }

    public function testCollectionExecutesNoParamInstruction()
    {
        $instructionBuilder = $this->getMockBuilder(InstructionInterface::class);
        $instructionMock = $instructionBuilder->setMethods(['getClassName','getMethod', 'getParams'])->getMock();
        $instructionMock->method('getClassName')->willReturn(ExecuteMe::class);
        $instructionMock->method('getMethod')->willReturn('noParams');

        $collection = $this->getCollection();
        $collection->addInstruction($instructionMock);
        $collection->execute();

        $executeMe = $this->getDi()->get(ExecuteMe::class);
        self::assertTrue($executeMe->noParams);
    }

    public function testCollectionThrowsExceptionWithInvalidAddObject()
    {
        $this->setExpectedException(\Error::class);
        $object = new \stdClass();

        $this->getCollection()->addInstruction($object);
    }

}