<?php

namespace Tests\Magium\Elements;

use Magium\AbstractConfigurableElement;
use Magium\AbstractTestCase;

class EnvironmentConfigurationReaderTest extends AbstractTestCase
{

    public function testEnvironmentVariableIsCapturedDirectlyFromEnvironment()
    {
        self::markTestSkipped('This test can only be run manually from the command line');
        /* This test can only be run manually from the CLI.  Prior to running the test you must execute
         * export MAGIUM_TESTS_MAGIUM_ELEMENTS_ENVIRONMENTELEMENT_test=2
        */
        $element = $this->get(__NAMESPACE__ . '\EnvironmentElement');
        self::assertEquals(2, $element->test);
    }

    public function testEnvironmentVariableIsCaptured()
    {
        $_ENV['MAGIUM_TESTS_MAGIUM_ELEMENTS_CONFIGURABLEELEMENT_test'] = 2;
        $element = $this->get(__NAMESPACE__ . '\ConfigurableElement');
        self::assertEquals(2, $element->test);
    }

    public function testServerVariableIsCaptured()
    {
        $_SERVER['MAGIUM_TESTS_MAGIUM_ELEMENTS_CONFIGURABLEELEMENT_test'] = 2;
        $element = $this->get(__NAMESPACE__ . '\ConfigurableElement');
        self::assertEquals(2, $element->test);
    }

    public function testEnvironmentVariableSupercedesServer()
    {
        $_SERVER['MAGIUM_TESTS_MAGIUM_ELEMENTS_CONFIGURABLEELEMENT_test'] = 2;
        $_ENV['MAGIUM_TESTS_MAGIUM_ELEMENTS_CONFIGURABLEELEMENT_test'] = 3;
        $element = $this->get(__NAMESPACE__ . '\ConfigurableElement');
        self::assertEquals(3, $element->test);
    }
}

class ConfigurableElement extends AbstractConfigurableElement
{

    public $test = 1;

}

class EnvironmentElement extends AbstractConfigurableElement
{

    public $test = 1;

}
