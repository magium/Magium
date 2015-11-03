<?php

namespace Tests\Magento\Customer;

use Magium\Magento\AbstractMagentoTestCase;

class ToAdminLoginTest extends AbstractMagentoTestCase
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
        $messages = $this->get('Magium\Magento\Admin\Messages');
        self::assertTrue($messages->hasMessages());
    }
    
}