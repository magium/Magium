<?php

namespace Tests\Magento\Customer;

use Magium\Magento\AbstractMagentoTestCase;

class SaveSystemConfigurationSettingTest extends AbstractMagentoTestCase
{

    public function testLoginAdmin()
    {
        $this->get('Magium\Magento\Actions\Admin\Login\Login')->login();
        $enabler = $this->get('Magium\Magento\Actions\Admin\Configuration\Enabler');
        /** @var $enabler \Magium\Magento\Actions\Admin\Configuration\Enabler */

        $enabler->disable('Payment Methods/Saved CC');

        $enabler->enable('Payment Methods/Saved CC');

        $enabler->enable('Payment Methods/Saved CC');
    }


    
}