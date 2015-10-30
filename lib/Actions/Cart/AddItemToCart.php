<?php

namespace Magium\Actions\Cart;

use Magium\WebDriver\WebDriver;
class AddItemToCart
{
    protected $webdriver;
    
    public function __construct(
        WebDriver $webdriver
    ) {
        $this->webdriver = $webdriver;
    }
    
    /**
     * Adds an item to the cart from its product page
     * @TODO
     * 
     * @throws \Magium\NotFoundException
     */
    
    public function addSimpleProductToCartFromPage()
    {
        
    }
    
    /**
     * Finds a product on the current, presumed, category page and attempts to add it to the cart
     * @todo
     * 
     * @param string $name The name of the product
     */
    
    public function addSimpleItemToCartFromCategoryPage($name = null)
    {
        
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