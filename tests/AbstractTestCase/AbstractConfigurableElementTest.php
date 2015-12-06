<?php

namespace Tests\Magium\AbstractTestCase;

class AbstractConfigurableElementTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslateAbstractConfigurableElementWithString()
    {
        $translator = $this->getMockBuilder('Zend\I18n\Translator\Translator')
            ->disableOriginalConstructor()
            ->setMethods(['translate'])
            ->getMock();
        $translator->method('translate')->willReturn('boogers');

        $abstractElement = $this->getMockBuilder('Magium\AbstractConfigurableElement')
            ->setConstructorArgs([$translator])
            ->setMethods(null)
            ->getMock();
        /* @var $abstractElement \Magium\AbstractConfigurableElement */

        $translated = $abstractElement->translate('{{kevin}}');
        self::assertEquals('boogers', $translated);

        $translated = $abstractElement->translate('abc{{kevin}}');
        self::assertEquals('abcboogers', $translated);

        $translated = $abstractElement->translate('{{kevin}}xyz');
        self::assertEquals('boogersxyz', $translated);

        $translated = $abstractElement->translate('abc{{kevin}}xyz');
        self::assertEquals('abcboogersxyz', $translated);

        $translated = $abstractElement->translate('{{kevin}}{{kevin}}');
        self::assertEquals('boogersboogers', $translated);

        $translated = $abstractElement->translate('{{kevin}} {{kevin}}');
        self::assertEquals('boogers boogers', $translated);
    }

    public function testTranslateAbstractConfigurableElementWithArray()
    {
        $translator = $this->getMockBuilder('Zend\I18n\Translator\Translator')
            ->disableOriginalConstructor()
            ->setMethods(['translate'])
            ->getMock();
        $translator->method('translate')->willReturn('boogers');

        $abstractElement = $this->getMockBuilder('Magium\AbstractConfigurableElement')
            ->setConstructorArgs([$translator])
            ->setMethods(null)
            ->getMock();
        /* @var $abstractElement \Magium\AbstractConfigurableElement */

        $translated = $abstractElement->translate(['{{kevin}}']);
        self::assertCount(1, $translated);
        self::assertEquals('boogers', $translated[0]);


        $translated = $abstractElement->translate(['a' => '{{kevin}}']);
        self::assertCount(1, $translated);
        self::assertEquals('boogers', $translated['a']);

    }

    public function testTranslateAbstractConfigurableElementDoesNotTranslateIncompletePlaceholders()
    {
        $translator = $this->getMockBuilder('Zend\I18n\Translator\Translator')
            ->disableOriginalConstructor()
            ->setMethods(['translate'])
            ->getMock();
        $translator->method('translate')->willReturn('boogers');

        $abstractElement = $this->getMockBuilder('Magium\AbstractConfigurableElement')
            ->setConstructorArgs([$translator])
            ->setMethods(null)
            ->getMock();
        /* @var $abstractElement \Magium\AbstractConfigurableElement */

        $translated = $abstractElement->translate('{kevin}}');
        self::assertEquals('{kevin}}', $translated);


        $translated = $abstractElement->translate('{{kevin}');
        self::assertEquals('{{kevin}', $translated);

    }
}