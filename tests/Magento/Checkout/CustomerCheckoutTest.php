<?php

namespace Tests\Magento\Checkout;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Checkout\Extractors\OrderId;

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
         $customerCheckout= $this->getAction('Checkout\CustomerCheckout');
        /* @var $customerCheckout \Magium\Magento\Actions\Checkout\CustomerCheckout */

        $customerCheckout->execute();

        $orderId = $this->getAction('Checkout\Extractors\OrderId');
        /** @var $orderId OrderId */
        self::assertNotNull($orderId->getOrderId());
        self::assertGreaterThan(0, $orderId->getOrderId());
    }

}