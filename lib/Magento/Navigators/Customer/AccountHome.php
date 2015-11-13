<?php

namespace Magium\Magento\Navigators\Customer;

use Magium\Magento\Themes\ThemeConfiguration;
use Magium\Navigators\InstructionNavigator;

class AccountHome
{

    protected $theme;
    protected $instructionsNavigator;

    public function __construct(
        ThemeConfiguration $theme,
        InstructionNavigator $instructionsNavigator

    )
    {
        $this->theme = $theme;
        $this->instructionsNavigator = $instructionsNavigator;
    }

    public function navigateTo()
    {

        $instructions = $this->theme->getNavigateToCustomerPageInstructions();
        $this->instructionsNavigator->navigateTo($instructions);

    }
}