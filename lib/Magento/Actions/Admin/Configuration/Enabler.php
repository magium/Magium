<?php

namespace Magium\Magento\Actions\Admin\Configuration;

use Magium\AbstractTestCase;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Navigators\Admin\AdminMenuNavigator;
use Magium\Magento\Navigators\Admin\SystemConfigurationNavigator;
use Magium\Magento\Themes\AdminThemeConfiguration;
use Magium\WebDriver\WebDriver;

class Enabler
{

    protected $webDriver;
    protected $adminMenuNavigator;
    protected $systemConfigurationNavigator;
    protected $adminThemeConfiguration;
    protected $testCase;
    protected $save;

    protected $tab;
    protected $section;

    public function __construct(
        WebDriver                       $webDriver,
        AdminMenuNavigator              $adminMenuNavigator,
        SystemConfigurationNavigator    $systemConfigurationNavigator,
        AdminThemeConfiguration         $adminThemeConfiguration,
        AbstractMagentoTestCase         $testCase,
        Save                            $save
    ) {
        $this->webDriver                    = $webDriver;
        $this->adminMenuNavigator           = $adminMenuNavigator;
        $this->systemConfigurationNavigator = $systemConfigurationNavigator;
        $this->adminThemeConfiguration      = $adminThemeConfiguration;
        $this->testCase                     = $testCase;
        $this->save                         = $save;
    }

    protected function setting($setting)
    {
        $parts = explode('/', $setting);
        $this->testCase->assertCount(2, $parts);
        $this->tab  = $parts[0];
        $this->section  = $parts[1];
    }

    protected function navigateToSystemConfiguration($setting)
    {
        if (!$this->webDriver->elementDisplayed($this->adminThemeConfiguration->getSystemConfigTabsXpath(), AbstractTestCase::BY_XPATH)) {
            $this->adminMenuNavigator->navigateTo('System/Configuration');
        }

        if (!$this->webDriver->elementDisplayed($this->adminThemeConfiguration->getSystemConfigSectionDisplayCheckXpath(), AbstractTestCase::BY_XPATH)) {
            $this->systemConfigurationNavigator->navigateTo($setting);
        }
    }

    public function enable($setting, $save = true)
    {
        $this->setting($setting);
        $this->navigateToSystemConfiguration($setting);
        $settingXpath = sprintf($this->adminThemeConfiguration->getSystemConfigToggleEnableXpath(), $this->section, 1);
        $this->testCase->assertElementDisplayed($settingXpath);
        $element = $this->webDriver->byXpath($settingXpath);
        if (!$element->getAttribute('selected')) {
            $element->click();
            if ($save) {
                $this->save->save();
            }
        }

    }

    public function disable($setting, $save = true)
    {
        $this->setting($setting);
        $this->navigateToSystemConfiguration($setting);
        $settingXpath = sprintf($this->adminThemeConfiguration->getSystemConfigToggleEnableXpath(), $this->section, 0);
        $this->testCase->assertElementDisplayed($settingXpath, AbstractTestCase::BY_XPATH);
        $element = $this->webDriver->byXpath($settingXpath);
        if ($element->getAttribute('selected')) {
            $element->click();
            if ($save) {
                $this->save->save();
            }
        }

    }

}