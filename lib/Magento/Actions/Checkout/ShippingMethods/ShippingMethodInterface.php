<?php

namespace Magium\Magento\Actions\Checkout\ShippingMethods;

interface ShippingMethodInterface
{

    public function choose($required);

}