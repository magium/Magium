<?php

namespace Tests\Magento\Customer;

use Magium\Magento\AbstractMagentoTestCase;

class ToCustomerLoginTest extends AbstractMagentoTestCase
{

    public function testNavigateToLogin()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator('Customer\AccountHome')->navigateTo();
        $this->assertElementHasText('h1', 'Login or Create an Account');
    }
    
    public function testLoginCustomer()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator('Customer\AccountHome')->navigateTo();
        $this->getAction('Customer\Login')->login();
        self::assertEquals('My Account', $this->webdriver->getTitle());
    }
    
    public function testLoginCustomerSucceedsWhenRequireLoginIsNotSetAndAccountIsAlreadyLoggedIn()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator('Customer\AccountHome')->navigateTo();
        $this->getAction('Customer\Login')->login();

        $this->getNavigator('Customer\AccountHome')->navigateTo();
        $this->getAction('Customer\Login')->login();
    }

    public function testLoginCustomerFailsWhenRequireLoginIsSetAndAccountIsAlreadyLoggedIn()
    {
        $this->setExpectedException('Facebook\WebDriver\Exception\NoSuchElementException');
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getNavigator('Customer\AccountHome')->navigateTo();
        $this->getAction('Customer\Login')->login();

        $this->getNavigator('Customer\AccountHome')->navigateTo();
        $this->getAction('Customer\Login')->login(null, null, true);

    }

    public function testNavigateAndLogin()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction('Customer\NavigateAndLogin')->login();

    }
}