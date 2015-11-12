<?php

namespace Magium\Magento\Actions\Checkout;

use Magium\Magento\Actions\Checkout\Extractors\OrderId;
use Magium\Magento\Actions\Checkout\Steps\CustomerBillingAddress;
use Magium\Magento\Actions\Checkout\Steps\LogInCustomer;
use Magium\Magento\Actions\Checkout\Steps\PaymentMethod;
use Magium\Magento\Actions\Checkout\Steps\PlaceOrder;
use Magium\Magento\Actions\Checkout\Steps\ReviewOrder;
use Magium\Magento\Actions\Checkout\Steps\SelectCustomerCheckout;
use Magium\Magento\Actions\Checkout\Steps\ShippingAddress;
use Magium\Magento\Actions\Checkout\Steps\ShippingMethod;
use Magium\Magento\Navigators\Checkout\CheckoutNavigator;
use Magium\Magento\Navigators\Checkout\CheckoutStartNavigator;
use Magium\Magento\Themes\OnePageCheckout\ThemeConfiguration as OnePageCheckoutTheme;

class CustomerCheckout extends AbstractCheckout
{

    public function __construct(
        CheckoutStartNavigator  $navigator,
        OnePageCheckoutTheme    $theme,
        LogInCustomer           $logInCustomer,
        CustomerBillingAddress  $billingAddress,
        ShippingAddress         $shippingAddress,
        ShippingMethod          $shippingMethod,
        PaymentMethod           $paymentMethod,
        PlaceOrder              $placeOrder,
        OrderId                 $orderIdExtractor
    )
    {
        $this->addStep($navigator);
        $this->addStep($logInCustomer);
        $this->addStep($billingAddress);
        $this->addStep($shippingAddress);
        $this->addStep($shippingMethod);
        $this->addStep($paymentMethod);
        $this->addStep($placeOrder);
        $this->addStep($orderIdExtractor);

    }

}