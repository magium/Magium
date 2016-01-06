<?php

namespace Tests\Magium\Util;

use Magium\Util\Translator\Translator;
use Zend\I18n\Translator\Loader\PhpMemoryArray;

class TranslatorTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslateAbstractConfigurableElementWithString()
    {

        $translator = $this->getTranslator([
            'kevin' => 'boogers',
            'Accessories'   => 'boogers',
            'Jewelry'   => 'boogers'
        ]);

        $translated = $translator->translate('{{kevin}}');
        self::assertEquals('boogers', $translated);

        $translated = $translator->translate('abc{{kevin}}');
        self::assertEquals('abcboogers', $translated);

        $translated = $translator->translate('{{kevin}}xyz');
        self::assertEquals('boogersxyz', $translated);

        $translated = $translator->translate('abc{{kevin}}xyz');
        self::assertEquals('abcboogersxyz', $translated);

        $translated = $translator->translate('{{kevin}}{{kevin}}');
        self::assertEquals('boogersboogers', $translated);

        $translated = $translator->translate('{{kevin}}/{{kevin}}');
        self::assertEquals('boogers/boogers', $translated);

        $translated = $translator->translate('{{Accessories}}/{{Jewelry}}');
        self::assertEquals('boogers/boogers', $translated);
    }

    public function testTranslateAbstractConfigurableElementWithArray()
    {
        $translator = $this->getTranslator([
            'kevin' => 'boogers'
        ]);

        $translated = $translator->translate(['{{kevin}}']);
        self::assertCount(1, $translated);
        self::assertEquals('boogers', $translated[0]);

        $translated = $translator->translate(['a' => '{{kevin}}']);
        self::assertCount(1, $translated);
        self::assertEquals('boogers', $translated['a']);

    }

    public function testTranslateAbstractConfigurableElementDoesNotTranslateIncompletePlaceholders()
    {
        $translator = $this->getTranslator([
            'kevin' => 'boogers'
        ]);

        $translated = $translator->translate('{kevin}}');
        self::assertEquals('{kevin}}', $translated);


        $translated = $translator->translate('{{kevin}');
        self::assertEquals('{{kevin}', $translated);

    }


    public function testTranslateAbstractConfigurableElementDoesNotTranslateWithoutPlaceholders()
    {
        $translator = $this->getTranslator([
            'kevin' => 'boogers'
        ]);

        $translated = $translator->translate('kevin');
        self::assertEquals('kevin', $translated);


    }

    /**
     * @param array $messages
     * @return Translator
     */

    protected function getTranslator(array $messages)
    {
        $loader = new PhpMemoryArray(
            [
                'default' => [
                    'en_US' => $messages
                ]
            ]
        );
        $translator = Translator::factory(array(
            'locale'                => 'en_US',
            'remote_translation'    => array(
                array(
                    'type'        => 'phpmemoryarray',
                ),
            ),
        ));
        $translator->getPluginManager()->setService('phpmemoryarray', $loader);
        $translator->setSkipServiceBuild(true);
        return $translator;
    }
}