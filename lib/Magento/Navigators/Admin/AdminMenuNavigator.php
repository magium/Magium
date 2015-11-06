<?php

namespace Magium\Magento\Navigators\Admin;

use Magium\Magento\Navigators\BaseMenuNavigator;
use Magium\Magento\Themes\AdminThemeConfiguration;
use Magium\WebDriver\WebDriver;

class AdminMenuNavigator extends BaseMenuNavigator
{


    public function __construct(AdminThemeConfiguration $theme, WebDriver $webdriver)
    {
        parent::__construct($theme, $webdriver);
    }
    
    
    
}