<?php


namespace Magium\Magento\Actions\Admin\Tables;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Themes\AdminThemeConfiguration;
use Magium\WebDriver\WebDriver;

class ClickButton
{

    protected $webDriver;
    protected $theme;
    protected $testCase;

    public function __construct(
        WebDriver                   $webDriver,
        AdminThemeConfiguration     $theme,
        AbstractMagentoTestCase     $testCase
    )
    {
        $this->webDriver                = $webDriver;
        $this->theme                    = $theme;
        $this->testCase                 = $testCase;
    }

    public function click($text)
    {
        $elementXpath = sprintf($this->theme->getTableButtonXpath(), $text);
        $this->testCase->assertElementDisplayed($elementXpath, WebDriver::BY_XPATH);

        $this->webDriver->byXpath($elementXpath)->click();

    }

}