<?php

namespace Magium\Magento\Navigators\Customer;

use Magium\Magento\Themes\Customer\ThemeConfiguration;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class NavigateToOrder
{

    protected $webDriver;
    protected $accountNavigator;
    protected $themeConfiguration;

    public function __construct(
        WebDriver               $webDriver,
        Account                 $accountNavigator,
        ThemeConfiguration      $themeConfiguration
    )
    {
        $this->webDriver            = $webDriver;
        $this->accountNavigator     = $accountNavigator;
        $this->themeConfiguration   = $themeConfiguration;
    }

    public function navigateTo($orderId)
    {
        $xpath = $this->themeConfiguration->getAccountNavigationXpath();
        $sectionXpath = sprintf($xpath, $this->themeConfiguration->getOrderPageName());
        $this->webDriver->byXpath($sectionXpath)->click();

        $xpath = $this->themeConfiguration->getViewOrderLinkXpath();
        $linkXpath = sprintf($xpath, $orderId);
        $this->webDriver->byXpath($linkXpath)->click();

        $this->webDriver->wait()->until(ExpectedCondition::titleContains($this->themeConfiguration->getOrderPageTitleContainsText()));

    }

}