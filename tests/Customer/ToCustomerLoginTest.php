<?php

namespace Customer;

use Magium\AbstractTestCase;

class ToCustomerLoginTest extends AbstractTestCase
{

    public function testNavigateToLogin()
    {
        $this->getCustomer()->navigateToLogin();
        $this->assertElementHasText('h1', 'Login or Create an Account');
    }
    
    public function testLoginCustomer()
    {
        $this->getCustomer()->login();
        self::assertEquals('My Account', $this->webdriver->getTitle());
    }
    
    public function testLoginCustomerSucceedsWhenRequireLoginIsNotSetAndAccountIsAlreadyLoggedIn()
    {
        $this->getCustomer()->login();
        $this->getCustomer()->login();
    }
    
    public function testLoginCustomerFailsWhenRequireLoginIsSetAndAccountIsAlreadyLoggedIn()
    {
        $this->setExpectedException('Facebook\WebDriver\Exception\NoSuchElementException');
        $this->getCustomer()->login();
        $this->getCustomer()->login(null, null, true);
    }
}