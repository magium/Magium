<?php

namespace Tests\Magento\Customer;

use Magium\Magento\AbstractMagentoTestCase;

class NavigateToOrder extends AbstractMagentoTestCase
{

    public function testCreateAndNavigateToOrder()
    {
        $this->commandOpen($this->getTheme()->getBaseUrl());
        $this->getAction('Cart\AddItemToCart')->addSimpleProductToCartFromCategoryPage();
        $this->setPaymentMethod('CashOnDelivery');
        $this->getAction('Checkout\CustomerCheckout')->execute();

        $orderId = $this->get('Magium\Magento\Actions\Checkout\Extractors\OrderId')->getOrderId();

        $this->getNavigator('Customer\AccountHome')->navigateTo();
        $this->getNavigator('Customer\NavigateToOrder')->navigateTo($orderId);

        $this->assertPageHasText($this->getIdentity()->getBillingFirstName());
    }

}