<?php

namespace Magium\Magento\Actions\Checkout\Steps;

use Facebook\WebDriver\WebDriverExpectedCondition;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Identities\Customer;
use Magium\Magento\Identities\CustomerIdentity;
use Magium\WebDriver\WebDriver;
use Magium\Magento\Themes\OnePageCheckout\ThemeConfiguration;
class BillingAddress implements StepInterface
{
    
    protected $webdriver;
    protected $theme;
    protected $customerIdentity;
    protected $testCase;
    
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
    
    public function execute()
    {
        $this->testCase->assertElementExists($this->theme->getBillingFirstNameXpath(), AbstractMagentoTestCase::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingLastNameXpath(), AbstractMagentoTestCase::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingCompanyXpath(), AbstractMagentoTestCase::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingEmailAddressXpath(), AbstractMagentoTestCase::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingAddressXpath(), AbstractMagentoTestCase::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingAddress2Xpath(), AbstractMagentoTestCase::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingCityXpath(), AbstractMagentoTestCase::BY_XPATH);
        $regionXpath = sprintf($this->theme->getBillingRegionIdXpath(), $this->customerIdentity->getBillingRegionId());
        $this->testCase->assertElementExists($regionXpath, AbstractMagentoTestCase::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingPostCodeXpath(), AbstractMagentoTestCase::BY_XPATH);
        $countryXpath = sprintf($this->theme->getBillingCountryIdXpath(), $this->customerIdentity->getBillingCountryId());
        $this->testCase->assertElementExists($countryXpath, AbstractMagentoTestCase::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingTelephoneXpath(), AbstractMagentoTestCase::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingFaxXpath(), AbstractMagentoTestCase::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getBillingContinueButtonXpath(), AbstractMagentoTestCase::BY_XPATH);

        $this->testCase->byXpath($this->theme->getBillingFirstNameXpath())->sendKeys($this->customerIdentity->getBillingFirstName());
        $this->testCase->byXpath($this->theme->getBillingLastNameXpath())->sendKeys($this->customerIdentity->getBillingLastName());
        $this->testCase->byXpath($this->theme->getBillingCompanyXpath())->sendKeys($this->customerIdentity->getBillingCompany());
        $this->testCase->byXpath($this->theme->getBillingEmailAddressXpath())->sendKeys($this->customerIdentity->getEmailAddress());
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

        $this->testCase->byXpath($this->theme->getBillingContinueButtonXpath())->click();

        $this->webdriver->wait()->until(WebDriverExpectedCondition::not(WebDriverExpectedCondition::visibilityOf($this->webdriver->byXpath($this->theme->getBillingContinueCompletedXpath()))));
        return true; // continue to next step
    }
}