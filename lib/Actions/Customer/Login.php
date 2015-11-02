<?php

namespace Magium\Actions\Customer;

use Magium\AbstractTestCase;
use Magium\Authenticators\CustomerAuthenticator;
use Magium\Navigators\InstructionNavigator;
use Magium\Themes\ThemeConfiguration;
use Magium\WebDriver\WebDriver;

class Login
{
    protected $webdriver;
    protected $theme;
    protected $testCase;
    protected $instructionsNavigator;
    protected $customerAuthenticator;


    public function __construct(
        WebDriver $webdriver,
        ThemeConfiguration $theme,
        InstructionNavigator $instructionsNavigator,
        CustomerAuthenticator $customerAuthenticator,
        AbstractTestCase $testCase

    ) {
        $this->webdriver    = $webdriver;
        $this->theme        = $theme;
        $this->testCase     = $testCase;
        $this->instructionsNavigator = $instructionsNavigator;
        $this->customerAuthenticator = $customerAuthenticator;
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
            $username = $this->customerAuthenticator->getAccount();
        }

        if ($password === null) {
            $password = $this->customerAuthenticator->getPassword();
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