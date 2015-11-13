<?php

namespace Magium\Magento\Actions\Checkout;

use Magium\Magento\Actions\Checkout\Extractors\OrderId;
use Magium\Magento\Actions\Checkout\Steps\BillingAddress;
use Magium\Magento\Actions\Checkout\Steps\NewCustomerPassword;
use Magium\Magento\Actions\Checkout\Steps\PaymentMethod;
use Magium\Magento\Actions\Checkout\Steps\PlaceOrder;
use Magium\Magento\Actions\Checkout\Steps\ReviewOrder;
use Magium\Magento\Actions\Checkout\Steps\SelectCustomerCheckout;
use Magium\Magento\Actions\Checkout\Steps\SelectRegisterNewCustomerCheckout;
use Magium\Magento\Actions\Checkout\Steps\ShippingAddress;
use Magium\Magento\Actions\Checkout\Steps\ShippingMethod;
use Magium\Magento\Navigators\Checkout\CheckoutNavigator;
use Magium\Magento\Navigators\Checkout\CheckoutStartNavigator;
use Magium\Magento\Themes\OnePageCheckout\ThemeConfiguration as OnePageCheckoutTheme;

class RegisterNewCustomerCheckout extends AbstractCheckout
{

    public function __construct(
        CheckoutStartNavigator  $navigator,
        OnePageCheckoutTheme    $theme,
        SelectRegisterNewCustomerCheckout           $registerNewCustomerCheckout,
        BillingAddress  $billingAddress,
        ShippingAddress         $shippingAddress,
        ShippingMethod          $shippingMethod,
        PaymentMethod           $paymentMethod,
        PlaceOrder              $placeOrder,
        OrderId                 $orderIdExtractor,
        NewCustomerPassword     $newCustomerPassword
    )
    {
        $this->addStep($navigator);
        $this->addStep($registerNewCustomerCheckout);
        $this->addStep($newCustomerPassword);
        $this->addStep($billingAddress);
        $this->addStep($shippingAddress);
        $this->addStep($shippingMethod);
        $this->addStep($paymentMethod);
        $this->addStep($placeOrder);
        $this->addStep($orderIdExtractor);

    }

}