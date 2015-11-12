<?php

namespace Magium\Magento\Assertions\Cart;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Assertions\AssertionInterface;
use Magium\Magento\Themes\ThemeConfiguration;

class AddToCartSucceeded implements AssertionInterface
{

    protected $testCase;
    protected $themeConfiguration;

    public function __construct(
        AbstractMagentoTestCase $testCase,
        ThemeConfiguration  $themeConfiguration
    )
    {
        $this->testCase             = $testCase;
        $this->themeConfiguration   = $themeConfiguration;
    }

    public function assert()
    {
        $this->testCase->assertElementDisplayed($this->themeConfiguration->getAddToCartSuccessXpath(), WebDriver::BY_XPATH);
    }

}