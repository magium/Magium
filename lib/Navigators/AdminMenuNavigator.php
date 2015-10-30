<?php

namespace Magium\Navigators;

use Magium\Themes\AdminThemeConfiguration;
class AdminMenuNavigator extends BaseMenuNavigator
{
    
    protected $themeConfiguration;
    
    public function __construct(AdminThemeConfiguration $theme)
    {
        parent::__construct($theme);
    }
    
    
    
}