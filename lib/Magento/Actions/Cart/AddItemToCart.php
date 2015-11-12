<?php

namespace Magium\Magento\Actions\Cart;

use Magium\Magento\AbstractMagentoTestCase;
use Magium\Magento\Navigators\BaseMenuNavigator;
use Magium\Magento\Themes\ThemeConfiguration;
use Magium\WebDriver\WebDriver;

class AddItemToCart
{
    protected $webdriver;
    protected $theme;
    protected $navigator;
    protected $testCase;
    
    public function __construct(
        WebDriver $webdriver,
        ThemeConfiguration $theme,
        BaseMenuNavigator $navigator,
        AbstractMagentoTestCase $testCase
    ) {
        $this->webdriver = $webdriver;
        $this->theme = $theme;
        $this->navigator = $navigator;
        $this->testCase = $testCase;
    }
    
    /**
     * Adds an item to the cart from its product page by navigating to the default
     * test category and adding the default test product to the cart.
     * 
     * @throws \Magium\NotFoundException
     */
    
    public function addSimpleProductToCartFromCategoryPage($categoryNavigationPath = null, $addToCartXpath = null)
    {
        if ($categoryNavigationPath === null) {
            $categoryNavigationPath = $this->theme->getNavigationPathToProductCategory();
        }

        if ($addToCartXpath === null) {
            $addToCartXpath = $this->theme->getCategoryAddToCartButtonXPathSelector();
        }

        $this->navigator->navigateTo($categoryNavigationPath);
        $this->testCase->assertElementExists($addToCartXpath, 'byXpath');
        $element = $this->webdriver->byXpath($addToCartXpath);
        $this->testCase->assertWebDriverElement($element);
        $element->click();
        $this->testCase->assertElementExists($this->theme->getAddToCartSuccessXpath(), 'byXpath');
    }
    
    /**
     * Finds a product on a category page and attempts to add it to the cart after navigating to the product page
     * @todo
     *
     * @param string $categoryNavigationPath The category path to go to
     * @param string $addToCartXpath The Xpath for adding a simple product the cart from the product4 page
     * @param string $productLinkXpath The Xpath to go to the product page
     */
    
    public function addSimpleItemToCartFromProductPage($productLinkXpath = null, $categoryNavigationPath = null, $addToCartXpath = null)
    {
        if ($categoryNavigationPath === null) {
            $categoryNavigationPath = $this->theme->getNavigationPathToProductCategory();
        }

        if ($addToCartXpath === null) {
            $addToCartXpath = $this->theme->getCategoryAddToCartButtonXPathSelector();
        }

        $this->navigator->navigateTo($categoryNavigationPath);

        $element = $this->webdriver->byXpath($this->theme->getProductPageForCategory());

        $this->testCase->assertWebDriverElement($element);

        $element->click();

        $element = $this->webdriver->byXpath($this->theme->getSimpleProductAddToCartXpath());

        $this->testCase->assertWebDriverElement($element);

        $element->click();

        $this->testCase->assertElementExists($this->theme->getAddToCartSuccessXpath(), 'byXpath');
    }
    
    /**
     * Adds as configurable product to the cart from the product page.  If option 
     * values are not specified it will try to find a combination that works.
     * 
     * @param array $options
     */
    
    public function addConfigurableItemToCartFromProductPage(array $options = null)
    {
        
    }
    /**
     * Will attempt to find a configurable product on the current category page, click on
     * its product link and add to cart from there.  A specific product can be stated, and its
     * options, or you can allow it to try and find the first available item
     * 
     * @param string $name (Optional) The name of the product
     * @param array $options (Optional) The options of the product
     */
    
    public function addConfigurableItemToCartFromCategoryPage($name = null, array $options = null)
    {
        
    }
}