<?php

namespace Tests\Magium\AbstractTestCase
{

    use Magium\AbstractTestCase;
    use Zend\Di\Exception\ClassNotFoundException;

    class BaseNamespaceTest extends AbstractTestCase
    {


        public function testAddingBaseNamespaceFailsWithoutClassResolution()
        {
            if (AbstractTestCase::isPHPUnit5()) {
                //PHPUnit 5
                $this->expectException('Zend\Di\Exception\ClassNotFoundException');
            } else {
                $this->expectException(ClassNotFoundException::class);
            }
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
