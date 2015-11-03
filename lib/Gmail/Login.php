<?php

namespace Magium\Gmail;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Magium\AbstractTestCase;
use Magium\Commands\Open;
use Magium\Gmail\AccountInformation;
use Magium\WebDriver\WebDriver;

class Login
{

    protected $accountInformation;
    protected $webdriver;
    protected $open;
    protected $testCase;

    public function __construct(
        AccountInformation  $accountInformation,
        WebDriver           $webDriver,
        Open                $open,
        AbstractTestCase    $testCase
    ) {
        $this->accountInformation = $accountInformation;
        $this->webdriver          = $webDriver;
        $this->open               = $open;
        $this->testCase           = $testCase;
    }

    public function login()
    {
        $this->open->open('http://www.gmail.com/');
        $this->testCase->assertElementExists('Email');
        $this->testCase->assertElementExists('next');
        $element = $this->webdriver->byId('Email');
        $element->sendKeys($this->accountInformation->getEmailAddress());

        $element = $this->webdriver->byId('next');
        $element->click();

        $this->webdriver->wait()->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('Passwd')));

        $this->testCase->assertElementExists('Passwd');
        $this->testCase->assertElementExists('signIn');

        $element = $this->webdriver->byId('Passwd');
        $element->sendKeys($this->accountInformation->getPassword());

        $element = $this->webdriver->byId('signIn');
        $element->click();

        $this->webdriver->wait()->until(WebDriverExpectedCondition::titleContains('Inbox'));

    }

}