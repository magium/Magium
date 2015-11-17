<?php

namespace Magium\Magento\Actions\Customer;

use Magium\Magento\Themes\ThemeConfiguration;
use Magium\Navigators\InstructionNavigator;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class Logout
{
    protected $webdriver;
    protected $theme;
    protected $instructionsNavigator;


    public function __construct(
        WebDriver               $webdriver,
        ThemeConfiguration      $theme,
        InstructionNavigator    $instructionsNavigator

    ) {
        $this->webdriver    = $webdriver;
        $this->theme        = $theme;
        $this->instructionsNavigator = $instructionsNavigator;
    }


    public function logout()
    {
        $this->instructionsNavigator->navigateTo($this->theme->getLogoutNavigationInstructions());
        $this->webdriver->wait()->until(ExpectedCondition::elementExists($this->theme->getLogoutSuccessXpath(), WebDriver::BY_XPATH));
    }
}