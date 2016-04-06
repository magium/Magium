<?php

namespace Tests\Magium\AbstractTestCase;

    use Magium\AbstractTestCase;

class WebDriverTest extends AbstractTestCase
{

    public function testWebDriverInstance()
    {
        $webDriver = $this->get('Magium\WebDriver\WebDriver');
        self::assertInstanceOf('Magium\WebDriver\WebDriver', $webDriver);

        $webDriver = $this->get('Facebook\WebDriver\WebDriver');
        self::assertInstanceOf('Magium\WebDriver\WebDriver', $webDriver);

        $webDriver = $this->get('Facebook\WebDriver\RemoteWebDriver');
        self::assertInstanceOf('Magium\WebDriver\WebDriver', $webDriver);

        $webDriver = $this->get('Facebook\WebDriver\WebDriver');
        self::assertSame($this->webdriver, $webDriver);


    }



}


