<?php

namespace Magium\Magento\Navigators\Customer;

use Magium\Navigators\InstructionNavigator;
use Magium\Magento\Themes\ThemeConfiguration;

class Login
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

    public function navigateToLogin()
    {

        $instructions = $this->theme->getLoginInstructions();
        $this->instructionsNavigator->navigateTo($instructions);

    }
}