<?php

namespace Tests\Magium\Elements;

use Magium\AbstractConfigurableElement;
use Magium\Util\Translator\Translator;

class AbstractConfigurableElementTest extends \PHPUnit_Framework_TestCase
{

    public function testPropertyPassedViaEnvironmentVariable()
    {
        $_ENV['MAGIUM_TESTS_MAGIUM_ELEMENTS_PROPERTYELEMENT_property'] = 'changed';
        $obj =  new PropertyElement(new Translator());
        self::assertEquals('changed', $obj->getProperty());
    }

    public function testTranslationSmokeTest()
    {
        $obj =  new PropertyElement(new Translator());
        $value = $obj->translate('{{Kevin}}');
        self::assertEquals('Kevin', $value);

        $value = $obj->translate('Kevin');
        self::assertEquals('Kevin', $value);

        $value = $obj->translate('Kevin}');
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