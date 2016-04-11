<?php

namespace Tests\Magium\AbstractTestCase
{

    use Magium\AbstractTestCase;

    class BaseNamespaceTest extends AbstractTestCase
    {


        public function testAddingBaseNamespaceFailsWithoutClassResolution()
        {
            $this->setExpectedException('Zend\Di\Exception\ClassNotFoundException');
            self::assertInstanceOf('ArbitraryNamespace\Navigators\TestNavigator', $this->getNavigator('TestNavigator'));
        }

        public function testAddingBaseNamespaceSucceedsWithClassResolution()
        {
            AbstractTestCase::addBaseNamespace('ArbitraryNamespace');
            self::assertInstanceOf('ArbitraryNamespace\Navigators\TestNavigator', $this->getNavigator('TestNavigator'));
        }

    }
}

namespace ArbitraryNamespace\Navigators
{

    class TestNavigator
    {

    }


}