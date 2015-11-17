<?php

namespace Magium\Magento\Actions\Customer;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Identities\Customer;
use Magium\Magento\Themes\ThemeConfiguration;
use Magium\Navigators\InstructionNavigator;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class Register
{
    protected $webdriver;
    protected $theme;
    protected $testCase;
    protected $instructionsNavigator;
    protected $customerIdentity;


    public function __construct(
        WebDriver               $webdriver,
        ThemeConfiguration      $theme,
        InstructionNavigator    $instructionsNavigator,
        Customer                $customerIdentity,
        AbstractMagentoTestCase $testCase

    ) {
        $this->webdriver    = $webdriver;
        $this->theme        = $theme;
        $this->testCase     = $testCase;
        $this->instructionsNavigator = $instructionsNavigator;
        $this->customerIdentity = $customerIdentity;
    }

    public function register($registerForNewsletter = false)
    {
        $this->instructionsNavigator->navigateTo($this->theme->getRegistrationNavigationInstructions());

        $firstnameElement  = $this->webdriver->byXpath($this->theme->getRegisterFirstNameXpath());
        $lastnameElement   = $this->webdriver->byXpath($this->theme->getRegisterLastNameXpath());
        $emailElement      = $this->webdriver->byXpath($this->theme->getRegisterEmailXpath());
        $passwordElement   = $this->webdriver->byXpath($this->theme->getRegisterPasswordXpath());
        $confirmElement    = $this->webdriver->byXpath($this->theme->getRegisterConfirmPasswordXpath());
        $submitElement     = $this->webdriver->byXpath($this->theme->getRegisterSubmitXpath());

        $firstnameElement->sendKeys($this->customerIdentity->getBillingFirstName());
        $lastnameElement->sendKeys($this->customerIdentity->getBillingLastName());
        $emailElement->sendKeys($this->customerIdentity->getEmailAddress());
        $passwordElement->sendKeys($this->customerIdentity->getPassword());
        $confirmElement->sendKeys($this->customerIdentity->getPassword());

        $submitElement->click();

        $this->webdriver->wait()->until(ExpectedCondition::titleIs($this->theme->getMyAccountTitle()));
    }
}