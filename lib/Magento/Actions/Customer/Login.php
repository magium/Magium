<?php

namespace Magium\Magento\Actions\Customer;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Identities\Customer;
use Magium\Magento\Themes\ThemeConfiguration;
use Magium\Navigators\InstructionNavigator;
use Magium\WebDriver\WebDriver;

class Login
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


    /**
     *
     * Will log in to the specified customer account.  If requireLogin is specified it will assert that
     * the login form MUST be there.  Otherwise it will return if the login form is not there, presuming
     * that the current session is already logged in.
     *
     * @param string $username
     * @param string $password
     * @param bool $requireLogin Fail the test if there is an account currently logged in
     */

    public function login($username = null, $password = null, $requireLogin = false)
    {

        if ($requireLogin) {
            $element = $this->webdriver->byXpath($this->theme->getLoginUsernameField());
            $this->testCase->assertNotNull($element);
        } else {
            try {
                $element = $this->webdriver->byXpath($this->theme->getLoginUsernameField());
                if ($element === null) {
                    return;
                }
                // If we're logged in we don't need to do the login process.  Continue along.
            } catch (\Facebook\WebDriver\Exception\NoSuchElementException $e ) {
                return;
            }
        }
        if ($username === null) {
            $username = $this->customerIdentity->getEmailAddress();
        }

        if ($password === null) {
            $password = $this->customerIdentity->getPassword();
        }

        $usernameElement = $this->webdriver->byXpath($this->theme->getLoginUsernameField());
        $passwordElement = $this->webdriver->byXpath($this->theme->getLoginPasswordField());
        $submitElement = $this->webdriver->byXpath($this->theme->getLoginSubmitButton());

        $this->testCase->assertInstanceOf('Facebook\Webdriver\WebDriverElement', $usernameElement);
        $this->testCase->assertInstanceOf('Facebook\Webdriver\WebDriverElement', $passwordElement);
        $this->testCase->assertInstanceOf('Facebook\Webdriver\WebDriverElement', $submitElement);


        $usernameElement->sendKeys($username);
        $passwordElement->sendKeys($password);
        $submitElement->click();
    }
}