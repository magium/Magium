<?php

namespace Magium\Magento\Navigators\Admin;

use Magium\WebDriver\WebDriver;
use Magium\Magento\Themes\ThemeConfiguration;
class SystemConfigurationNavigator
{
    
    protected $webdriver;
    protected $themeConfiguration;

    public function __construct(ThemeConfiguration $theme, WebDriver $webdriver)
    {
        $this->themeConfiguration = $theme;
        $this->webdriver = $webdriver;
    }
    
    public function navigateTo($path)
    {
        // @TODO Build the navigator so it will navigate to the correct system configuration setting.
        
    }
    
}