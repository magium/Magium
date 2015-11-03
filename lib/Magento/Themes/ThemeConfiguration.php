<?php

namespace Magium\Magento\Themes;

use Magium\AbstractConfigurableElement;

class ThemeConfiguration extends AbstractConfigurableElement
{

    /**
     * @var string The Xpath string that finds the base of the navigation menu
     */
    protected $navigationBaseXPathSelector          = '//nav[@id="nav"]/ol';

    /**
     * @var string The Xpath string that can be used iteratively to find child navigation nodes
     */

    protected $navigationChildXPathSelector         = 'li[contains(concat(" ",normalize-space(@class)," ")," level%d ")]/a[.="%s"]/..';

    /**
     * @var string A simple, default path to use for categories.
     */

    protected $navigationPathToProductCategory      = 'Accessories/Jewelry';

    /**
     * @var string Xpath to add a Simple product to the cart from the product's page
     */

    protected $simpleProductAddToCartXpath          = '//button[@title="Add to Cart" and @onclick]';

    /**
     * @var string Xpath to add a Simple product to the cart from the category page
     */

    protected $categoryAddToCartButtonXPathSelector = '//button[@title="Add to Cart" and @onclick]';

    /**
     * @var string Xpath to find a product's link on a category page.  Used to navigate to the product from the category
     */

    protected $categoryProductPageXpath             = '//h2[@class="product-name"]/descendant::a';

    /**
     * @var string Xpath for the customer email form element
     */

    protected $loginUsernameField           = '//input[@type="email" and @id="email"]';

    /**
     * @var string Xpath for the customer password form element
     */

    protected $loginPasswordField           = '//input[@type="password" and @id="pass"]';


    /**
     * @var string Xpath for the customer login "submit" button
     */

    protected $loginSubmitButton            = '//button[@id="send2"]';


    /**
     * @var string Xpath used after a product has been added to the cart to verify that the product has been added to the cart
     */

    protected $addToCartSuccessXpath        = '//li[@class="success-msg" and contains(., "was added to your shopping cart")]';

    /**
     * @var string The base URL of the installation
     */

    protected $baseUrl                      = 'http://localhost/';

    /**
     * @var array Instructions in an Xpath array syntax to get to the login page.
     */
    
    protected $loginInstructions            = [
        [\Magium\WebDriver\WebDriver::INSTRUCTION_MOUSE_CLICK, '//div[@class="account-cart-wrapper"]/descendant::span[.="Account"]'],
        [\Magium\WebDriver\WebDriver::INSTRUCTION_MOUSE_CLICK, '//div[@id="header-account"]/descendant::a[@title="My Account"]']
    ];
    
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
    public function getProductPageForCategory()
    {
        return $this->categoryProductPageXpath;
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