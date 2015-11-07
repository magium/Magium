<?php

namespace Magium\Magento\Navigators\Admin;

use Magium\AbstractTestCase;
use Magium\InvalidInstructionException;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;
use Magium\Magento\Themes\AdminThemeConfiguration;
class SystemConfigurationNavigator
{
    
    protected $webdriver;
    protected $themeConfiguration;
    protected $testCase;

    public function __construct(
        AdminThemeConfiguration $theme,
        WebDriver $webdriver,
        AbstractMagentoTestCase $testCase
    ) {
        $this->themeConfiguration   = $theme;
        $this->webdriver            = $webdriver;
        $this->testCase             = $testCase;
    }
    
    public function navigateTo($path)
    {
        $instructions = explode('/', $path);
        if (count($instructions) !== 2) {
            throw new InvalidInstructionException('System Configuration instructions need to be in the format of "Tab/Section"');
        }
        $tabXpath = sprintf($this->themeConfiguration->getSystemConfigTabsXpath(), $instructions[0]);
        $sectionDisplayXpath = sprintf($this->themeConfiguration->getSystemConfigSectionDisplayCheckXpath(), $instructions[1]);
        $sectionToggleXpath = sprintf($this->themeConfiguration->getSystemConfigSectionToggleXpath(), $instructions[1]);

        $this->testCase->assertElementExists($tabXpath, AbstractTestCase::BY_XPATH);

        $this->webdriver->byXpath($tabXpath)->click();

        $this->webdriver->wait()->until(ExpectedCondition::elementExists($sectionDisplayXpath, AbstractTestCase::BY_XPATH));

        $this->testCase->assertElementExists($sectionToggleXpath, AbstractTestCase::BY_XPATH);
        if (!$this->webdriver->elementDisplayed($sectionDisplayXpath, AbstractTestCase::BY_XPATH)) {
            $this->webdriver->byXpath($sectionToggleXpath)->click();
        }
    }
    
}