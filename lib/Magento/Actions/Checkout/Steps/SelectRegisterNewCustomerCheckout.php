<?php

namespace Magium\Magento\Actions\Checkout\Steps;

use Magium\AbstractTestCase;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Identities\Customer;
use Magium\Magento\Themes\OnePageCheckout\ThemeConfiguration;
use Magium\WebDriver\WebDriver;

class SelectRegisterNewCustomerCheckout implements StepInterface
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

        if (!$this->customer->isUniqueEmailAddressGenerated()) {
            $this->customer->generateUniqueEmailAddress();
        }


        $this->testCase->assertElementExists($this->theme->getRegisterNewCustomerCheckoutButtonXpath(), AbstractTestCase::BY_XPATH);
        $element = $this->webdriver->byXpath($this->theme->getRegisterNewCustomerCheckoutButtonXpath());
        $this->testCase->assertWebDriverElement($element);
        $element->click();

        $this->testCase->assertElementExists($this->theme->getContinueButtonXpath(), AbstractTestCase::BY_XPATH);
        $element = $this->webdriver->byXpath($this->theme->getContinueButtonXpath());
        $this->testCase->assertWebDriverElement($element);
        $element->click();

        return true;
    }
}