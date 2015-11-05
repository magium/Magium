<?php

namespace Magium\Magento\Admin;

use Facebook\WebDriver\Exception\WebDriverException;
use Magium\Commands\Open;
use Magium\Magento\Themes\AdminThemeConfiguration;
use Magium\Magento\Identities\AdminIdentity;
use Magium\Navigators\InstructionNavigator;
use Magium\WebDriver\WebDriver;
use Magium\Magento\AbstractMagentoTestCase;
use Facebook\WebDriver\WebDriverExpectedCondition;
class Account
{
    
    protected $theme;
    protected $adminIdentity;
    protected $webdriver;
    protected $testCase;
    protected $openCommand;
    protected $messages;
    
    public function __construct(
        AdminThemeConfiguration $theme,
        AdminIdentity      $adminIdentity,
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

        $this->openCommand->open($this->adminIdentity->getUrl());

        $usernameElement = $this->webdriver->byXpath($this->theme->getLoginUsernameField());
        $passwordElement = $this->webdriver->byXpath($this->theme->getLoginPasswordField());
        $submitElement   = $this->webdriver->byXpath($this->theme->getLoginSubmitButton());

        $this->testCase->assertInstanceOf('Facebook\Webdriver\WebDriverElement', $usernameElement);
        $this->testCase->assertInstanceOf('Facebook\Webdriver\WebDriverElement', $passwordElement);
        $this->testCase->assertInstanceOf('Facebook\Webdriver\WebDriverElement', $submitElement);

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