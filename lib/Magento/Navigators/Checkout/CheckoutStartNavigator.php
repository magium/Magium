<?php

namespace Magium\Magento\Navigators\Checkout;

use Magium\Navigators\InstructionNavigator;

class CheckoutNavigator extends InstructionNavigator
{

    public function navigateTo()
    {
        $instructions = $this->themeConfiguration->getCheckoutNavaigationInstructions();
        return parent::navigateTo($instructions);
    }

}