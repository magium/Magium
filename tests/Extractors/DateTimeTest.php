<?php

namespace Tests\Magium\Extractors;

use Magium\Extractors\DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{

    public function testBasicDate()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('DECEMBER 31, 2015');
        $dateTime->extract();
        self::assertEquals('DECEMBER 31, 2015', $dateTime->getDateString());
    }

    public function testBasicDateTextInFront()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('Order placed on DECEMBER 31, 2015');
        $dateTime->extract();
        self::assertEquals('DECEMBER 31, 2015', $dateTime->getDateString());
    }

    public function testBasicDateTextAtEnd()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('DECEMBER 31, 2015 Was when your order was placed');
        $dateTime->extract();
        self::assertEquals('DECEMBER 31, 2015', $dateTime->getDateString());
    }

    public function testBasicDateTextAtEndAndBeginning()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('For your convenience DECEMBER 31, 2015 was when your order was placed');
        $dateTime->extract();
        self::assertEquals('DECEMBER 31, 2015', $dateTime->getDateString());
    }

    public function testBasicDateTextAtEndAndBeginningWithTime()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('For your convenience DECEMBER 31, 2015 01:01:01 was when your order was placed');
        $dateTime->extract();
        self::assertEquals('DECEMBER 31, 2015 01:01:01', $dateTime->getDateString());
    }

    public function testDateVariation1()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('DEC 31, 2015');
        $dateTime->extract();
        self::assertEquals('DEC 31, 2015', $dateTime->getDateString());
    }


    public function testDateVariation2WithPostText()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('DEC 31 Of This Year');
        $dateTime->extract();
        self::assertEquals('DEC 31', $dateTime->getDateString());
    }


    public function testDateVariation3()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('01/01/2016');
        $dateTime->extract();
        self::assertEquals('01/01/2016', $dateTime->getDateString());
    }


    public function testDateVariation3WithPre()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('The date is 01/01/2016');
        $dateTime->extract();
        self::assertEquals('01/01/2016', $dateTime->getDateString());
    }


    public function testDateVariation3WithPost()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('01/01/2016 if you care');
        $dateTime->extract();
        self::assertEquals('01/01/2016', $dateTime->getDateString());
    }


    public function testDateVariation3WithBoth()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('The date is 01/01/2016 if you care at all');
        $dateTime->extract();
        self::assertEquals('01/01/2016', $dateTime->getDateString());
    }


    public function testDateVariation3WithPrePostAndTime()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('The date is 01/01/2016 01:01:02 if you care');
        $dateTime->extract();
        self::assertEquals('01/01/2016 01:01:02', $dateTime->getDateString());
    }

    public function testDateVariation3WithPrePostTimeAndMeridiemVariation1()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('The date is 01/01/2016 01:01:02pm if you care');
        $dateTime->extract();
        self::assertEquals('01/01/2016 01:01:02pm', $dateTime->getDateString());
    }


    public function testDateVariation3WithPrePostTimeAndMeridiemVariation2()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('The date is 01/01/2016 01:01:02 pm if you care');
        $dateTime->extract();
        self::assertEquals('01/01/2016 01:01:02 pm', $dateTime->getDateString());
    }


    public function testDateVariation4WithPrePostTimeAndMeridiem()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('Your order 101325342 was placed on 01/01/2016 01:01:02 pm if you care');
        $dateTime->extract();
        self::assertEquals('01/01/2016 01:01:02 pm', $dateTime->getDateString());
    }

    public function testDateVariation5WithPrePostTimeAndMeridiem()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('Your order 101325342 was placed on 01-01-2016 01:01:02 pm if you care');
        $dateTime->extract();
        self::assertEquals('01-01-2016 01:01:02 pm', $dateTime->getDateString());
    }

    public function testTimezoneDetected()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('DECEMBER 13, 2015 CST');
        $dateTime->extract();
        self::assertEquals('DECEMBER 13, 2015 CST', $dateTime->getDateString());
    }

    public function testTimezoneDetectedWithTime()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('DECEMBER 13, 2015 01:01:01 CST');
        $dateTime->extract();
        self::assertEquals('DECEMBER 13, 2015 01:01:01 CST', $dateTime->getDateString());
    }



    public function testWithActualOrderText()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('ORDER DATE: DECEMBER 30, 2015');
        $dateTime->extract();
        self::assertEquals('DECEMBER 30, 2015', $dateTime->getDateString());

    }

    public function testWithActualOrderTex2()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('ORDER DATE: JANUARY 1, 2016');
        $dateTime->extract();
        self::assertEquals('JANUARY 1, 2016', $dateTime->getDateString());

    }

    public function testIncorrectTimeIgnored()
    {
        $dateTime = $this->getDateTimeObject();
        $dateTime->setText('DECEMBER 13, 2015 12:32:100');
        $dateTime->extract();
        self::assertEquals('DECEMBER 13, 2015', $dateTime->getDateString());
    }

    protected function getDateTimeObject()
    {
        $webDriver = $this->getMockBuilder('Magium\WebDriver\WebDriver')->disableOriginalConstructor();
        $testCase = $this->getMockBuilder('Magium\AbstractTestCase');
        return new DateTime(
            $webDriver->getMock(),
            $testCase->getMock(),
            $this->createMock('Magium\Themes\ThemeConfigurationInterface')
        );
    }

}
