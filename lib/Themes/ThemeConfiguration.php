<?php

namespace Magium\Themes;

use Magium\AbstractConfigurableElement;

class ThemeConfiguration extends AbstractConfigurableElement
{
 
    
    protected $navigationBaseXPathSelector          = '//nav[@id="nav"]/ol';
    protected $navigationChildXPathSelector         = 'li[contains(concat(" ",normalize-space(@class)," ")," level%d ")]/a[.="%s"]/..';
    protected $navigationPathToProductCategory      = 'Accessories/Jewelry';
    protected $simpleProductAddToCartXpath          = '//a[@title="Blue Horizons Bracelets"]/../descendant::button';
    protected $categoryAddToCartButtonXPathSelector = '//button[@title="Add to Cart" and @onclick]';
    
    protected $loginUsernameField           = '//input[@type="email" and @id="email"]';
    protected $loginPasswordField           = '//input[@type="password" and @id="pass"]';
    protected $loginSubmitButton            = '//button[@id="send2"]';

    protected $addToCartSuccessXpath        = '//li[@class="success-msg" and contains(., "was added to your shopping cart")]';

    protected $baseUrl                      = 'http://localhost/';
    
    protected $loginInstructions            = [
        [\Magium\WebDriver\WebDriver::INSTRUCTION_MOUSE_CLICK, '//div[@class="account-cart-wrapper"]/descendant::span[.="Account"]'],
        [\Magium\WebDriver\WebDriver::INSTRUCTION_MOUSE_CLICK, '//div[@id="header-account"]/descendant::a[@title="My Account"]']
    ];
    
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getAddToCartSuccessXpath()
    {
        return $this->addToCartSuccessXpath;
    }
    
    public function getLoginInstructions()
    {
        return $this->loginInstructions;
    }
    
    public function getNavigationBaseXPathSelector()
    {
        return $this->navigationBaseXPathSelector;
    }
    
    public function getNavigationChildXPathSelector()
    {
        return $this->navigationChildXPathSelector;
    }
    
    public function getNavigationPathToProductCategory()
    {
        return $this->navigationPathToProductCategory;
    }
    
    public function getCategoryAddToCartButtonXPathSelector()
    {
        return $this->categoryAddToCartButtonXPathSelector;
    }
    
    public function getLoginUsernameField()
    {
        return $this->loginUsernameField;
    }

    public function getSimpleProductAddToCartXpath()
    {
        return $this->simpleProductAddToCartXpath;
    }
    
    public function getLoginPasswordField()
    {
        return $this->loginPasswordField;
    } 
    
    public function getLoginSubmitButton()
    {
        return $this->loginSubmitButton;
    }
    
}