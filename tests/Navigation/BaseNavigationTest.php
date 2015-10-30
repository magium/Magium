<?php

namespace Navigation;

use Magium\AbstractTestCase;

class BaseNavigationTest extends AbstractTestCase
{

    public function testNavigateToJewelry()
    {
        $this->commandOpen('http://magento19.loc/');
        $this->getNavigator()->navigateTo('Accessories/Jewelry');
        $this->assertPageHasText('Blue Horizons Bracelets');
    }
}