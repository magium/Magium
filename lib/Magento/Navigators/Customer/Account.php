<?php

namespace Magium\Magento\Navigators\Customer;

use Magium\Magento\Themes\Customer\ThemeConfiguration;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class Account
{

    protected $webDriver;
    protected $themeConfiguration;

    public function __construct(
        WebDriver               $webDriver,
        ThemeConfiguration      $themeConfiguration
    )
    {
        $this->webDriver            = $webDriver;
        $this->themeConfiguration   = $themeConfiguration;
    }

    /**
     * Navigates to the section of the account management based off the section provided.  IF the header value is provided
     * it will issue a wait() command until the proper page header exists before returning.  This takes into account
     * the possibility of a section being loaded by Ajax while also retaining compatibility with templates based off
     * of the core template
     *
     * @param $section
     * @param null $header The title of the page
     */

    public function navigateTo($section, $header = null)
    {
        $xpath = $this->themeConfiguration->getAccountNavigationXpath();
        $sectionSelector = sprintf($xpath, $section);
        $element = $this->webDriver->byXpath($sectionSelector);
        $element->click();

        if ($header !== null) {
            $xpath = $this->themeConfiguration->getAccountSectionHeaderXpath();
            $headerSelector = sprintf($xpath, $header);
            $this->webDriver->wait()->until(ExpectedCondition::elementExists($headerSelector, WebDriver::BY_XPATH));
        }
    }

}