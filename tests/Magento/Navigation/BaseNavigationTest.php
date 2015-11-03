<?php

namespace Tests\Magento\Navigation;

use Magium\Magento\AbstractMagentoTestCase;

class BaseNavigationTest extends AbstractMagentoTestCase
{

    public function testNavigateToJewelry()
    {
        $this->commandOpen('http://magento19.loc/');
        $this->getNavigator()->navigateTo('Accessories/Jewelry');
        $this->assertPageHasText('Blue Horizons Bracelets');
    }
}