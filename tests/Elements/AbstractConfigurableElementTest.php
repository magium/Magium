<?php

namespace Tests\Magium\Elements;

use Magium\AbstractConfigurableElement;
use Magium\AbstractTestCase;
use Magium\Util\Configuration\StandardConfigurationProvider;
use Magium\Util\Translator\Translator;

class AbstractConfigurableElementTest extends AbstractTestCase
{

    public function testPropertyPassedViaEnvironmentVariable()
    {
        $_ENV['MAGIUM_TESTS_MAGIUM_ELEMENTS_PROPERTYELEMENT_property'] = 'changed';
        $obj =  new PropertyElement(new StandardConfigurationProvider());
        $obj->setTranslator(new Translator());
        self::assertEquals('changed', $obj->getProperty());
    }

    public function testTranslationSmokeTest()
    {
        $obj =  new PropertyElement(new StandardConfigurationProvider());
        $obj->setTranslator($this->getTranslator());
        $value = $obj->translatePlaceholders('{{Kevin}}');
        self::assertEquals('Kevin', $value);

        $value = $obj->translatePlaceholders('Kevin');
        self::assertEquals('Kevin', $value);

        $value = $obj->translatePlaceholders('Kevin}');
        self::assertEquals('Kevin}', $value);
    }

    public function testInjection()
    {
        $obj = $this->get('Tests\Magium\Elements\PropertyElement');
        self::assertInstanceOf('Magium\Util\Translator\Translator', $obj->getTranslator());
    }

    public function testInclusion()
    {
        $obj =  new PropertyElement(new StandardConfigurationProvider('include-file.php'));
        self::assertEquals(2, $obj->getValue());
        self::assertEquals(1, $obj->property);
    }

}

class PropertyElement extends AbstractConfigurableElement
{

    public $property = 'original';
    public $value = null;

    public function getProperty()
    {
        return $this->property;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}