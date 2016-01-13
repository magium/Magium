<?php

namespace Tests\Magium\Elements;

use Magium\AbstractConfigurableElement;
use Magium\AbstractTestCase;
use Magium\Util\Translator\Translator;

class AbstractConfigurableElementTest extends AbstractTestCase
{

    public function testPropertyPassedViaEnvironmentVariable()
    {
        $_ENV['MAGIUM_TESTS_MAGIUM_ELEMENTS_PROPERTYELEMENT_property'] = 'changed';
        $obj =  new PropertyElement();
        $obj->setTranslator(new Translator());
        self::assertEquals('changed', $obj->getProperty());
    }

    public function testTranslationSmokeTest()
    {
        $obj =  new PropertyElement();
        $obj->setTranslator($this->getTranslator());
        $value = $obj->translatePlaceholders('{{Kevin}}');
        self::assertEquals('Kevin', $value);

        $value = $obj->translatePlaceholders('Kevin');
        self::assertEquals('Kevin', $value);

        $value = $obj->translatePlaceholders('Kevin}');
        self::assertEquals('Kevin}', $value);
    }

}

class PropertyElement extends AbstractConfigurableElement
{

    protected $property = 'original';

    public function getProperty()
    {
        return $this->property;
    }
}