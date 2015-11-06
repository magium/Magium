<?php

namespace Magium\Magento\Actions\Admin\Configuration;

use Magium\AbstractTestCase;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Themes\AdminThemeConfiguration;
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
        $this->webDriver->action()->moveToElement($this->webDriver->byXpath('//body'));
        $this->testCase->assertElementDisplayed($this->adminThemeConfiguration->getSystemConfigurationSaveButtonXpath(), AbstractTestCase::BY_XPATH);
        $this->webDriver->byXpath($this->adminThemeConfiguration->getSystemConfigurationSaveButtonXpath())->click();
        $this->testCase->assertElementDisplayed($this->adminThemeConfiguration->getSystemConfigSaveSuccessfulXpath(), AbstractMagentoTestCase::BY_XPATH);
    }

}