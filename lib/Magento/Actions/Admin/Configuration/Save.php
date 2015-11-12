<?php

namespace Magium\Magento\Actions\Admin\Configuration;

use Magium\AbstractTestCase;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Themes\AdminThemeConfiguration;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class Save
{

    protected $webDriver;
    protected $adminThemeConfiguration;
    protected $testCase;

    public function __construct(
        WebDriver                   $webDriver,
        AdminThemeConfiguration     $adminThemeConfiguration,
        AbstractMagentoTestCase     $testCase
    ) {
        $this->webDriver                = $webDriver;
        $this->adminThemeConfiguration  = $adminThemeConfiguration;
        $this->testCase                 = $testCase;
    }

    public function save()
    {

        $this->webDriver->executeScript('window.scrollTo(0, 0);');

        $this->webDriver->wait()->until(ExpectedCondition::elementExists($this->adminThemeConfiguration->getSystemConfigurationSaveButtonXpath(), AbstractTestCase::BY_XPATH));
        $this->testCase->assertElementDisplayed($this->adminThemeConfiguration->getSystemConfigurationSaveButtonXpath(), AbstractTestCase::BY_XPATH);
        $this->webDriver->byXpath($this->adminThemeConfiguration->getSystemConfigurationSaveButtonXpath())->click();
        $this->testCase->assertElementDisplayed($this->adminThemeConfiguration->getSystemConfigSaveSuccessfulXpath(), WebDriver::BY_XPATH);
    }

}