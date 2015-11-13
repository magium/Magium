<?php

namespace Magium\Magento\Actions\Checkout\Steps;

use Facebook\WebDriver\WebDriverExpectedCondition;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Identities\Customer;
use Magium\Magento\Themes\OnePageCheckout\ThemeConfiguration;
use Magium\WebDriver\WebDriver;

class BillingAddress implements StepInterface
{
    
    protected $webdriver;
    protected $theme;
    protected $customerIdentity;
    protected $testCase;

    protected $bypass = [];
    
    public function __construct(
        WebDriver                   $webdriver,
        ThemeConfiguration          $theme,
        Customer            $customerIdentity,
        AbstractMagentoTestCase     $testCase
    ) {
        $this->webdriver        = $webdriver;
        $this->theme            = $theme;
        $this->customerIdentity = $customerIdentity;
        $this->testCase         = $testCase;
    }

    /**
     * Allows you to bypass arbitrary element assertions and entry.  Currently only supports email address.
     *
     * @see Magium\Magento\Actions\Checkout\Steps\CustomerBillingAddress
     *
     * @param $element The name of the element
     */

    public function bypassElement($element)
    {
        $this->bypass[] = $element;
    }

    public function execute()
    {
        if ($this->webdriver->elementDisplayed($this->theme->getBillingAddressDropdownXpath(), WebDriver::BY_XPATH)) {
            // We're logged in and we have an address.
            $this->clickContinue();
            return true;
        }

        $this->testCase->assertElementExists($this->theme->getBillingFirstNameXpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingLastNameXpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingCompanyXpath(), WebDriver::BY_XPATH);
        if (!in_array($this->theme->getBillingEmailAddressXpath(), $this->bypass)) {
            $this->testCase->assertElementExists($this->theme->getBillingEmailAddressXpath(), WebDriver::BY_XPATH);
        }
        $this->testCase->assertElementExists($this->theme->getBillingAddressXpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingAddress2Xpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingCityXpath(), WebDriver::BY_XPATH);
        $regionXpath = sprintf($this->theme->getBillingRegionIdXpath(), $this->customerIdentity->getBillingRegionId());
        $this->testCase->assertElementExists($regionXpath, WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingPostCodeXpath(), WebDriver::BY_XPATH);
        $countryXpath = sprintf($this->theme->getBillingCountryIdXpath(), $this->customerIdentity->getBillingCountryId());
        $this->testCase->assertElementExists($countryXpath, WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingTelephoneXpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingFaxXpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingContinueButtonXpath(), WebDriver::BY_XPATH);

        $this->testCase->byXpath($this->theme->getBillingFirstNameXpath())->sendKeys($this->customerIdentity->getBillingFirstName());
        $this->testCase->byXpath($this->theme->getBillingLastNameXpath())->sendKeys($this->customerIdentity->getBillingLastName());
        $this->testCase->byXpath($this->theme->getBillingCompanyXpath())->sendKeys($this->customerIdentity->getBillingCompany());
        if (!in_array($this->theme->getBillingEmailAddressXpath(), $this->bypass)) {
            $this->testCase->byXpath($this->theme->getBillingEmailAddressXpath())->sendKeys($this->customerIdentity->getEmailAddress());
        }
        $this->testCase->byXpath($this->theme->getBillingAddressXpath())->sendKeys($this->customerIdentity->getBillingAddress());
        $this->testCase->byXpath($this->theme->getBillingAddress2Xpath())->sendKeys($this->customerIdentity->getBillingAddress2());
        $this->testCase->byXpath($this->theme->getBillingCityXpath())->sendKeys($this->customerIdentity->getBillingCity());
        $regionXpath = sprintf($this->theme->getBillingRegionIdXpath(), $this->customerIdentity->getBillingRegionId());
        $this->testCase->byXpath($regionXpath)->click();
        $this->testCase->byXpath($this->theme->getBillingPostCodeXpath())->sendKeys($this->customerIdentity->getBillingPostCode());
        $countryXpath = sprintf($this->theme->getBillingCountryIdXpath(), $this->customerIdentity->getBillingCountryId());
        $this->testCase->byXpath($countryXpath)->click();
        $this->testCase->byXpath($this->theme->getBillingTelephoneXpath())->sendKeys($this->customerIdentity->getBillingTelephone());
        $this->testCase->byXpath($this->theme->getBillingFaxXpath())->sendKeys($this->customerIdentity->getBillingFax());


        $this->clickContinue();

        return true; // continue to next step
    }

    protected function clickContinue()
    {
        $this->testCase->byXpath($this->theme->getBillingContinueButtonXpath())->click();

        $this->webdriver->wait()->until(WebDriverExpectedCondition::not(WebDriverExpectedCondition::visibilityOf($this->webdriver->byXpath($this->theme->getBillingContinueCompletedXpath()))));
    }
}