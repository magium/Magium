<?php

namespace Magium\Magento\Actions\Checkout\Steps;

use Magium\AbstractTestCase;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Identities\Customer;
use Magium\Magento\Themes\OnePageCheckout\ThemeConfiguration;
use Magium\WebDriver\WebDriver;

class ShippingAddress implements StepInterface
{

    protected $webdriver;
    protected $theme;
    protected $customerIdentity;
    protected $testCase;

    public function __construct(
        WebDriver                   $webdriver,
        ThemeConfiguration          $theme,
        Customer                    $customerIdentity,
        AbstractMagentoTestCase     $testCase
    ) {
        $this->webdriver        = $webdriver;
        $this->theme            = $theme;
        $this->customerIdentity = $customerIdentity;
        $this->testCase         = $testCase;
    }

    public function execute()
    {
        // We will bypass ourself if the billing address is the same as the shipping address.
        if (!$this->webdriver->elementDisplayed($this->theme->getShippingFirstNameXpath(), AbstractTestCase::BY_XPATH)) {
            return true;
        }

        $this->testCase->assertElementExists($this->theme->getShippingFirstNameXpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getShippingLastNameXpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getShippingCompanyXpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getShippingAddressXpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getShippingAddress2Xpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getShippingCityXpath(), WebDriver::BY_XPATH);
        $regionXpath = sprintf($this->theme->getShippingRegionIdXpath(), $this->customerIdentity->getShippingRegionId());
        $this->testCase->assertElementExists($regionXpath, WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getShippingPostCodeXpath(), WebDriver::BY_XPATH);
        $countryXpath = sprintf($this->theme->getShippingCountryIdXpath(), $this->customerIdentity->getShippingCountryId());
        $this->testCase->assertElementExists($countryXpath, WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getShippingTelephoneXpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getShippingFaxXpath(), WebDriver::BY_XPATH);
        $this->testCase->assertElementExists($this->theme->getShippingContinueButtonXpath(), WebDriver::BY_XPATH);

        $this->testCase->byXpath($this->theme->getShippingFirstNameXpath())->sendKeys($this->customerIdentity->getShippingFirstName());
        $this->testCase->byXpath($this->theme->getShippingLastNameXpath())->sendKeys($this->customerIdentity->getShippingLastName());
        $this->testCase->byXpath($this->theme->getShippingCompanyXpath())->sendKeys($this->customerIdentity->getShippingCompany());
        $this->testCase->byXpath($this->theme->getShippingAddressXpath())->sendKeys($this->customerIdentity->getShippingAddress());
        $this->testCase->byXpath($this->theme->getShippingAddress2Xpath())->sendKeys($this->customerIdentity->getShippingAddress2());
        $this->testCase->byXpath($this->theme->getShippingCityXpath())->sendKeys($this->customerIdentity->getShippingCity());
        $regionXpath = sprintf($this->theme->getShippingRegionIdXpath(), $this->customerIdentity->getShippingRegionId());
        $this->testCase->byXpath($regionXpath)->click();
        $this->testCase->byXpath($this->theme->getShippingPostCodeXpath())->sendKeys($this->customerIdentity->getShippingPostCode());
        $countryXpath = sprintf($this->theme->getShippingCountryIdXpath(), $this->customerIdentity->getShippingCountryId());
        $this->testCase->byXpath($countryXpath)->click();
        $this->testCase->byXpath($this->theme->getShippingTelephoneXpath())->sendKeys($this->customerIdentity->getShippingTelephone());
        $this->testCase->byXpath($this->theme->getShippingFaxXpath())->sendKeys($this->customerIdentity->getShippingFax());

        $this->testCase->byXpath($this->theme->getShippingContinueButtonXpath())->click();

        $this->webdriver->wait()->until(WebDriverExpectedCondition::not(WebDriverExpectedCondition::visibilityOf($this->webdriver->byXpath($this->theme->getShippingContinueCompletedXpath()))));

        return true;
    }
}