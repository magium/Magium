<?php

namespace Magium\Magento\Actions\Checkout\PaymentMethods;

interface PaymentMethodInterface
{
    public function pay($requirePayment);
}