<?php

namespace Tests\Magento\Admin;

use Magium\Magento\AbstractMagentoTestCase;

class SystemConfigurationNavigationTest extends AbstractMagentoTestCase
{

    public function testConfigPanelOpened()
    {
        $this->getAction('Admin\Login\Login')->login();
        $adminMenuNavigator = $this->getNavigator('Admin\AdminMenuNavigator');
        $adminMenuNavigator->navigateTo('System/Configuration');

        $navigator = $this->getNavigator('Admin\SystemConfigurationNavigator');
        /** @var $navigator \Magium\Magento\Navigators\Admin\SystemConfigurationNavigator */
        $navigator->navigateTo('Payment Methods/Saved CC');
        $this->assertElementDisplayed('payment_ccsave_active');

        $navigator->navigateTo('Shipping Methods/Free Shipping');
        $this->assertElementDisplayed('carriers_freeshipping_active');

    }

}