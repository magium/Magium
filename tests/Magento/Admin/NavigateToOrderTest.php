<?php

namespace Tests\Magento\Admin;

use Magium\Magento\AbstractMagentoTestCase;

class NavigateToOrderTest extends AbstractMagentoTestCase
{

    public function testAdminNavigationToOrderSucceeds()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $this->getLogger()->info('Opening page ' . $theme->getBaseUrl());
        $addToCart = $this->getAction('Cart\AddItemToCart');
        /* @var $addToCart \Magium\Magento\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleProductToCartFromCategoryPage();
        $this->setPaymentMethod('CashOnDelivery');
        $guestCheckout = $this->getAction('Checkout\GuestCheckout');
        /* @var $guestCheckout \Magium\Magento\Actions\Checkout\GuestCheckout */

        $guestCheckout->execute();

        $orderId = $this->getAction('Checkout\Extractors\OrderId')->getOrderId();


        $this->getAction('Admin\Login\Login')->login();
        $this->getNavigator('Admin\OrderNavigator')->navigateTo($orderId);

        $this->assertPageHasText($orderId);

    }

}