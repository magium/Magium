<?php

namespace Magium\Magento\Actions\Admin\Login;

use Facebook\WebDriver\Exception\WebDriverException;
use Magium\Commands\Open;
use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Identities\Admin;
use Magium\Magento\Themes\AdminThemeConfiguration;
use Magium\Navigators\InstructionNavigator;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class Login
{
    
    protected $theme;
    protected $adminIdentity;
    protected $webdriver;
    protected $testCase;
    protected $openCommand;
    protected $messages;
    
    public function __construct(
        AdminThemeConfiguration $theme,
        Admin      $adminIdentity,
        InstructionNavigator    $instructionsNavigator,
        WebDriver               $webdriver,
        AbstractMagentoTestCase        $testCase,
        Open                    $open,
        Messages                $messages
    ) {
        $this->theme         = $theme;
        $this->adminIdentity = $adminIdentity;
        $this->webdriver     = $webdriver;
        $this->testCase      = $testCase;
        $this->openCommand   = $open;
        $this->messages      = $messages;
    }
    
    public function login($username = null, $password = null)
    {
        // We break SOLID here there might be scenarios where multiple logins are required.  So for expediency's sake
        // We're having the login action take responsibility for figuring out how to get to the login screen.

        $url = $this->webdriver->getCurrentURL();
        if (strpos($url, 'http') === false) {
            $this->openCommand->open($this->theme->getBaseUrl());
        } else {
            $this->webdriver->navigate()->to($this->theme->getBaseUrl());
            $title = $this->webdriver->getTitle();
            if (strpos($title, 'Dashboard') !== false) {
                return;
            }
        }

        $usernameElement = $this->webdriver->byXpath($this->theme->getLoginUsernameField());
        $passwordElement = $this->webdriver->byXpath($this->theme->getLoginPasswordField());
        $submitElement   = $this->webdriver->byXpath($this->theme->getLoginSubmitButton());

        $this->testCase->assertWebDriverElement($usernameElement);
        $this->testCase->assertWebDriverElement($passwordElement);
        $this->testCase->assertWebDriverElement($submitElement);

        if ($username === null) {
            $username = $this->adminIdentity->getAccount();
        }
        
        if ($password === null) {
            $password = $this->adminIdentity->getPassword();
        }
        
        $usernameElement->sendKeys($username);
        $passwordElement->sendKeys($password);
        
        $submitElement->click();
        $this->webdriver->wait(10)->until(ExpectedCondition::titleContains('Dashboard'));

        $this->extractMessages();

    }


    public function extractMessages()
    {
        try {
            $element = $this->webdriver->byXpath($this->theme->getAdminPopupMessageContainerXpath());
            $this->testCase->assertInstanceOf('Facebook\Webdriver\WebDriverElement', $element);
            $this->messages->addMessage($element->getText());

            $closeElement = $this->webdriver->byXpath($this->theme->getAdminPopupMessageCloseButtonXpath());
            $this->testCase->assertInstanceOf('Facebook\Webdriver\WebDriverElement', $closeElement);
            $closeElement->click();
        } catch (WebDriverException $e) {
            // Indicates that no popup messages were found when logging in.  Nothing needs to be done
        }
    }
    
}