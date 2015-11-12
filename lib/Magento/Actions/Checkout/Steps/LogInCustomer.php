<?php

namespace Magium\Magento\Actions\Checkout\Steps;

use Magium\AbstractTestCase;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Identities\Customer;
use Magium\Magento\Themes\OnePageCheckout\ThemeConfiguration;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class LogInCustomer implements StepInterface
{
    
    protected $webdriver;
    protected $theme;
    protected $testCase;
    protected $customer;
    
    public function __construct(
        WebDriver               $webdriver,
        ThemeConfiguration      $theme,
        AbstractMagentoTestCase $testCase,
        Customer                $customer
    ) {
        $this->webdriver    = $webdriver;
        $this->theme        = $theme;
        $this->testCase     = $testCase;
        $this->customer     = $customer;
    }
    
    public function execute()
    {
        if ($this->webdriver->elementDisplayed($this->theme->getBillingFirstNameXpath())) {
            return true; // We're already logged in
        }
        $this->testCase->assertElementExists($this->theme->getCustomerEmailInputXpath(), AbstractTestCase::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getCustomerPasswordInputXpath(), AbstractTestCase::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getCustomerButtonXpath(), AbstractTestCase::BY_XPATH);

        $emailInput = $this->webdriver->byXpath($this->theme->getCustomerEmailInputXpath());
        $emailInput->sendKeys($this->customer->getEmailAddress());

        $passwordInput = $this->webdriver->byXpath($this->theme->getCustomerPasswordInputXpath());
        $passwordInput->sendKeys($this->customer->getPassword());

        $button = $this->webdriver->byXpath($this->theme->getCustomerButtonXpath());
        $button->click();

        $this->webdriver->wait()->until(ExpectedCondition::elementExists($this->theme->getBillingFirstNameXpath(), AbstractTestCase::BY_XPATH));

        return true;
    }
}