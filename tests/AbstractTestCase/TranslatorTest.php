<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;

;

class TranslatorTest extends AbstractTestCase
{

    public function testTranslatorBaseConfiguration()
    {
        $translator = $this->getTranslator();
        self::assertInstanceOf('Zend\I18n\Translator\Translator', $translator);
        self::assertEquals('Test Value', $translator->translate('Test Value'));
    }

}