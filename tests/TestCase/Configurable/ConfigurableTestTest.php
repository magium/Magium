<?php

namespace Tests\Magium\TestCase\Configurable;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\WebDriverCurlException;
use Magium\AbstractTestCase;
use Magium\Assertions\Xpath\Exists;
use Magium\Assertions\Xpath\NotExists;
use Magium\TestCase\Configurable\GenericInstruction;
use Magium\TestCase\Configurable\Instruction;
use Magium\TestCase\Configurable\InstructionInterface;
use Magium\TestCase\Configurable\InstructionsCollection;
use Magium\TestCase\Configurable\Interpolator;
use Magium\TestCase\Configurable\InvalidInstructionException;
use Zend\Log\Writer\WriterInterface;

class ConfigurableTestTest extends AbstractTestCase
{

    protected $fileName;

    protected function getCollection()
    {
        return $this->getDi()->get(InstructionsCollection::class);
    }

    public function testCollectionAddsInstruction()
    {
        $instructionMock = $this->createMock(InstructionInterface::class);
        $this->getCollection()->addInstruction($instructionMock);
    }

    public function testCollectionExecutesNoParamInstruction()
    {
        $instruction = new GenericInstruction(ExecuteMe::class, 'noParams');

        $collection = $this->getCollection();
        $collection->addInstruction($instruction);
        $collection->execute();

        $executeMe = $this->getDi()->get(ExecuteMe::class);
        self::assertTrue($executeMe->noParams);
    }

    protected function setUpInterpolated()
    {
        $this->fileName = tempnam(sys_get_temp_dir(), uniqid());
        $uniqueValue = uniqid();
        $this->getDi()->instanceManager()->addAlias('executeMe', ExecuteMe::class);
        $this->get('executeMe')->withParam($uniqueValue);
        file_put_contents($this->fileName, <<<HTML
<html>
<body>
<div id="{$uniqueValue}">This is something</div>
</body>
</html>
HTML
        );
    }

    public function testCollectionInterpolation()
    {
        $this->setUpInterpolated();
        $this->commandOpen('file://' . $this->fileName);

        $interpolator = $this->get(Interpolator::class);

        $instruction = new GenericInstruction(Exists::class, 'assertSelector', [
            $interpolator->interpolate('//div[@id="{{$executeMe->withParam}}"]')
        ]);

        $collection = $this->getCollection();
        $collection->addInstruction($instruction);
        $collection->execute();

    }

    public function testCollectionInterpolationFails()
    {
        $this->setExpectedException(\PHPUnit_Framework_AssertionFailedError::class);
        $this->setUpInterpolated();
        $this->commandOpen('file://' . $this->fileName);

        $interpolator = $this->get(Interpolator::class);

        $instruction = new GenericInstruction(NotExists::class, 'assertSelector', [
            $interpolator->interpolate('//div[@id="{{$executeMe->withParam}}"]')
        ]);

        $collection = $this->getCollection();
        $collection->addInstruction($instruction);
        $collection->execute();

    }

    public function testExceptionIsBubbled()
    {
        $this->expectException(NoSuchElementException::class);
        $this->setUpInterpolated();
        $this->commandOpen('file://' . $this->fileName);

        $interpolator = $this->get(Interpolator::class);

        $instruction = new GenericInstruction(Exists::class, 'assertSelector', [
            $interpolator->interpolate('//div[@id="boogers"]')
        ]);

        $collection = $this->getCollection();
        $collection->addInstruction($instruction);
        $collection->execute();
    }

    public function testErrorIsLogged()
    {
        $this->setUpInterpolated();
        $this->commandOpen('file://' . $this->fileName);
        $logEvent = null;
        $writer = $this->createMock(WriterInterface::class);
        $writer->method('write')->with(
            $this->callback(function() use (&$logEvent) {
                $logEvent = func_get_args();
                return true;
            })
        );
        $this->getLogger()->addWriter($writer);

        $instruction = new GenericInstruction(Exists::class, 'assertSelector', [
            '//div[@id="boogers"]'
        ]);

        $collection = $this->getCollection();
        $collection->addInstruction($instruction);
        try {
            $collection->execute();
        } catch (\Exception $e) {
        }
        self::assertCount(1, $logEvent);
        self::assertArrayHasKey('message', $logEvent[0]);
        self::assertArrayHasKey('extra', $logEvent[0]);
        self::assertEquals('ERR', $logEvent[0]['priorityName']);
    }

    public function testCollectionThrowsExceptionWithInvalidAddObject()
    {
        $this->setExpectedException(\Error::class);
        $object = new \stdClass();

        $this->getCollection()->addInstruction($object);
    }

    protected function tearDown()
    {
        if ($this->fileName && file_exists($this->fileName)) {
            unlink($this->fileName);
        }
        parent::tearDown();
    }

}
