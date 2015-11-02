<?php

namespace Customer;

use Magium\AbstractTestCase;

class ToCustomerLoginTest extends AbstractTestCase
{

    public function testNavigateToLogin()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->get('Magium\Navigators\Customer\Login')->navigateToLogin();
        $this->assertElementHasText('h1', 'Login or Create an Account');
    }
    
    public function testLoginCustomer()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->get('Magium\Navigators\Customer\Login')->navigateToLogin();
        $this->get('Magium\Actions\Customer\Login')->login();
        self::assertEquals('My Account', $this->webdriver->getTitle());
    }
    
    public function testLoginCustomerSucceedsWhenRequireLoginIsNotSetAndAccountIsAlreadyLoggedIn()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->get('Magium\Navigators\Customer\Login')->navigateToLogin();
        $this->get('Magium\Actions\Customer\Login')->login();

        $this->get('Magium\Navigators\Customer\Login')->navigateToLogin();
        $this->get('Magium\Actions\Customer\Login')->login();
    }

    public function testLoginCustomerFailsWhenRequireLoginIsSetAndAccountIsAlreadyLoggedIn()
    {
        $this->setExpectedException('Facebook\WebDriver\Exception\NoSuchElementException');
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->get('Magium\Navigators\Customer\Login')->navigateToLogin();
        $this->get('Magium\Actions\Customer\Login')->login();

        $this->get('Magium\Navigators\Customer\Login')->navigateToLogin();
        $this->get('Magium\Actions\Customer\Login')->login(null, null, true);

    }

    public function testNavigateAndLogin()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->get('Magium\Actions\Customer\NavigateAndLogin')->login();

    }
}