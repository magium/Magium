<?php

namespace Magium\Magento\Actions\Admin\Login;

use Facebook\WebDriver\Exception\WebDriverException;
use Magium\Commands\Open;
use Magium\Magento\Identities\Admin;
use Magium\Magento\Themes\AdminThemeConfiguration;
use Magium\Magento\Identities\AdminIdentity;
use Magium\Navigators\InstructionNavigator;
use Magium\WebDriver\WebDriver;
use Magium\Magento\AbstractMagentoTestCase;
use Facebook\WebDriver\WebDriverExpectedCondition;
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

        $this->openCommand->open($this->theme->getBaseUrl());

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
        $this->webdriver->wait(10)->until(WebDriverExpectedCondition::titleContains('Dashboard'));

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