<?php

namespace Magium\Magento\Navigators\Checkout;

use Magium\Magento\Actions\Checkout\Steps\StepInterface;
use Magium\Navigators\InstructionNavigator;

class CheckoutStartNavigator extends InstructionNavigator implements StepInterface
{

    public function navigateTo(array $instructions = null)
    {
        if ($instructions === null) {
            $instructions = $this->themeConfiguration->getCheckoutNavigationInstructions();
        }
        return parent::navigateTo($instructions);
    }

    public function execute()
    {
        $this->navigateTo();
        return true;
    }

}