<?php

namespace Tests\Magium\AbstractTestCase;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Magium\AbstractTestCase;

class WebDriverTest extends AbstractTestCase
{

    public function testWebDriverInstance()
    {
        $webDriver = $this->get(\Magium\WebDriver\WebDriver::class);
        self::assertInstanceOf(\Magium\WebDriver\WebDriver::class, $webDriver);

        $webDriver = $this->get(\Facebook\WebDriver\WebDriver::class);
        self::assertInstanceOf(\Magium\WebDriver\WebDriver::class, $webDriver);

        $webDriver = $this->get(RemoteWebDriver::class);
        self::assertInstanceOf(\Magium\WebDriver\WebDriver::class, $webDriver);

        $webDriver = $this->get(\Facebook\WebDriver\WebDriver::class);
        self::assertSame($this->webdriver, $webDriver);


    }



}


