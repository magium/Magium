<?php

namespace Magium\Magento\Navigators;

use Magium\Magento\Themes\AdminThemeConfiguration;
use Magium\WebDriver\WebDriver;

class AdminMenuNavigator extends BaseMenuNavigator
{
    
    protected $themeConfiguration;

    public function __construct(AdminThemeConfiguration $theme, WebDriver $webdriver)
    {
        parent::__construct($theme, $webdriver);
    }
    
    
    
}