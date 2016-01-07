<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;

class TranslatorTest extends AbstractTestCase
{

    public function testTranslatorBaseConfiguration()
    {
        $translator = $this->getTranslator();
        self::assertInstanceOf('Magium\Util\Translator\Translator', $translator);
        self::assertEquals('Test Value', $translator->translate('Test Value'));
    }

    public function testTypePreference()
    {
        $translator = $this->get('Zend\I18n\Translator\Translator');
        self::assertInstanceOf('Magium\Util\Translator\Translator', $translator);
    }

}