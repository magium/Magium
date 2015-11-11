<?php

namespace Tests\Magento\Customer;

use Magium\Magento\AbstractMagentoTestCase;

class ToAdminLoginTest extends AbstractMagentoTestCase
{

    public function testLoginAdmin()
    {

        $this->getAction('Admin\Login\Login')->login();
        self::assertEquals('Dashboard / Magento Admin', $this->webdriver->getTitle());
    }


    public function testLoginAdminSucceedsWhenAlreadyLoggedIn()
    {

        $this->getAction('Admin\Login\Login')->login();
        self::assertEquals('Dashboard / Magento Admin', $this->webdriver->getTitle());

        $this->getAction('Admin\Login\Login')->login();
        self::assertEquals('Dashboard / Magento Admin', $this->webdriver->getTitle());
    }

    public function testAdminMessage()
    {
        self::markTestSkipped('This test can only be run if there is a popup message');
        $this->getAction('Admin\Login\Login')->login();
        $messages = $this->get('Magium\Magento\Admin\Messages');
        self::assertTrue($messages->hasMessages());
    }
    
}