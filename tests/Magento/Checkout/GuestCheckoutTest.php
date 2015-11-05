<?php

namespace Tests\Magento\Checkout;

use Magium\Magento\AbstractMagentoTestCase;

class GuestCheckoutTest extends AbstractMagentoTestCase
{

    public function testBasicCheckout()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $this->getLogger()->info('Opening page ' . $theme->getBaseUrl());
        $addToCart = $this->get('Magium\Magento\Actions\Cart\AddItemToCart');
        /* @var $addToCart \Magium\Magento\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleProductToCartFromCategoryPage();
        $this->setPaymentMethod('CashOnDelivery');
        $guestCheckout = $this->get('Magium\Magento\Actions\Checkout\GuestCheckout');
        /* @var $guestCheckout \Magium\Magento\Actions\Checkout\GuestCheckout */

        $guestCheckout->execute();
    }

}