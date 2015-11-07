<?php

namespace Tests\Magento\Navigation;

use Magium\Magento\AbstractMagentoTestCase;

class BaseNavigationTest extends AbstractMagentoTestCase
{

    public function testNavigateToJewelry()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $this->getNavigator()->navigateTo($theme->getNavigationPathToProductCategory());
        $this->assertPageHasText('Blue Horizons Bracelets');
    }
}