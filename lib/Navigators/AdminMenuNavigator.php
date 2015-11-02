<?php

namespace Magium\Navigators;

use Magium\Themes\AdminThemeConfiguration;
class AdminMenuNavigator extends BaseMenuNavigator
{
    
    protected $themeConfiguration;

    public function __construct(AdminThemeConfiguration $theme, WebDriver $webdriver)
    {
        parent::__construct($theme, $webdriver);
    }
    
    
    
}