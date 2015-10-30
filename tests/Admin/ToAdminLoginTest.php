<?php

namespace Customer;

use Magium\AbstractTestCase;

class ToAdminLoginTest extends AbstractTestCase
{

    public function testLoginAdmin()
    {
        $this->getAdminAccount()->login();
        self::assertEquals('Dashboard / Magento Admin', $this->webdriver->getTitle());
    }

    public function testAdminMessage()
    {
        self::markTestSkipped('This test can only be run if there is a popup message');
        $this->getAdminAccount()->login();
        $messages = $this->get('Magium\Admin\Messages');
        self::assertTrue($messages->hasMessages());
    }
    
}