<?php

namespace Admin;

use Magium\AbstractTestCase;

class AdminNavigationTest extends AbstractTestCase
{

    public function testNavigateToSystemConfiguration()
    {

        $this->getAdminAccount()->login();
        $this->getAdminNavigator()->navigateTo('System/Configuration');
        self::assertEquals('Configuration / System / Magento Admin', $this->webdriver->getTitle());
    }
}