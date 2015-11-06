<?php

namespace Magium\Magento\Actions\Checkout\ShippingMethods;

use Magium\AbstractTestCase;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Themes\OnePageCheckout\ThemeConfiguration;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class FirstAvailable implements ShippingMethodInterface
{

    protected $webDriver;
    protected $theme;
    protected $testCase;

    public function __construct(
        WebDriver               $webDriver,
        ThemeConfiguration      $theme,
        AbstractMagentoTestCase $testCase
    ) {
        $this->webDriver        = $webDriver;
        $this->theme            = $theme;
        $this->testCase         = $testCase;
    }

    public function choose($required)
    {
        $this->webDriver->wait()->until(
            ExpectedCondition::elementExists(
                $this->theme->getShippingMethodFormXpath(), AbstractTestCase::BY_XPATH
            )
        );

        // Some products, such as virtual products, do not get shipped
        if ($required) {
            $this->testCase->assertElementExists($this->theme->getDefaultShippingXpath(), AbstractTestCase::BY_XPATH);
            $this->testCase->assertElementDisplayed($this->theme->getDefaultShippingXpath(), AbstractTestCase::BY_XPATH);
        }

        if ($this->webDriver->elementDisplayed($this->theme->getDefaultShippingXpath(), AbstractTestCase::BY_XPATH)) {
            $this->webDriver->byXpath($this->theme->getDefaultShippingXpath())->click();
        }
    }

}