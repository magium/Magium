<?php

namespace Tests\Magento\Admin;

use Magium\Magento\AbstractMagentoTestCase;

class AdminNavigationTest extends AbstractMagentoTestCase
{

    public function testNavigateToSystemConfiguration()
    {

        $this->getAdminAccount()->login();
        $this->getAdminNavigator()->navigateTo('System/Configuration');
        self::assertEquals('Configuration / System / Magento Admin', $this->webdriver->getTitle());
    }
}