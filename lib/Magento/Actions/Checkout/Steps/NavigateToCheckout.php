<?php

namespace Magium\Magento\Actions\Checkout\Steps;

use Magium\WebDriver\WebDriver;
use Magium\Magento\Themes\ThemeConfiguration;
class NavigateToCheckout implements StepInterface
{
    
    protected $webdriver;
    protected $theme;
    
    public function __construct(
        WebDriver $webdriver,
        ThemeConfiguration $theme
    ) {
        $this->webdriver    = $webdriver;
        $this->theme        = $theme;
    }
    
    public function execute()
    {
        
    }
}