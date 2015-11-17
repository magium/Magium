<?php

namespace Tests\Magento\Admin;

use Magium\Magento\AbstractMagentoTestCase;

class TestClearTableFilters extends AbstractMagentoTestCase
{

    public function testFilterClearsSuccessfully()
    {

        $this->getAction('Admin\Login\Login')->login();
        $this->getNavigator('Admin\AdminMenuNavigator')->navigateTo('Sales/Orders');
        $this->webdriver->byId('sales_order_grid_filter_billing_name')->sendKeys('Kevin Schroeder');
        $this->webdriver->byXpath('//span[.="Search"]')->click();
        $this->getAction('Admin\WaitForLoadingMask')->wait();

        $element = $this->webdriver->byId('sales_order_grid_filter_billing_name');
        self::assertEquals('Kevin Schroeder', $element->getAttribute('value'));

        // Actual test

        $this->getAction('Admin\Tables\ClearTableFilters')->clear();


        $element = $this->webdriver->byId('sales_order_grid_filter_billing_name');
        self::assertEquals('', $element->getAttribute('value'));

    }

}