<?php

namespace Magium\Magento\Actions\Checkout\Steps;

use Facebook\WebDriver\WebDriverExpectedCondition;
use Magium\AbstractTestCase;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Actions\Checkout\PaymentMethods\PaymentMethodInterface;
use Magium\Magento\Themes\OnePageCheckout\ThemeConfiguration;
use Magium\WebDriver\WebDriver;

class PaymentMethod implements StepInterface
{

    protected $webdriver;
    protected $theme;
    protected $testCase;
    protected $paymentMethod;

    protected $requirePayment = false;

    public function __construct(
        WebDriver                   $webdriver,
        ThemeConfiguration          $theme,
        AbstractMagentoTestCase     $testCase,
        PaymentMethodInterface      $paymentMethod
    ) {
        $this->webdriver        = $webdriver;
        $this->theme            = $theme;
        $this->testCase         = $testCase;
        $this->paymentMethod    = $paymentMethod;
    }

    public function requirePayment($require = true)
    {
        $this->requirePayment = $require;
    }
    
    public function execute()
    {
        /* Given that there is the possibility of either a) products with $0, and b) payment methods that do not use
         * the standard form we do not fail if we cannot find payment elements
         */

        $this->paymentMethod->pay($this->requirePayment);

        $this->testCase->assertElementExists($this->theme->getPaymentMethodContinueButtonXpath(), AbstractTestCase::BY_XPATH);
        $this->webdriver->byXpath($this->theme->getPaymentMethodContinueButtonXpath())->click();

        $this->webdriver->wait()->until(
            WebDriverExpectedCondition::not(
                WebDriverExpectedCondition::visibilityOf(
                    $this->webdriver->byXpath(
                        $this->theme->getPaymentMethodContinueCompleteXpath()
                    )
                )
            )
        );
        return true;
    }
}