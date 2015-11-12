<?php

namespace Tests\Magento\Checkout;

use Magium\Magento\AbstractMagentoTestCase;

class AddItemToCartTest extends AbstractMagentoTestCase
{

    public function testSimpleAddToCartWithDefaults()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $this->getLogger()->info('Opening page ' . $theme->getBaseUrl());
        $addToCart = $this->getAction('Cart\AddItemToCart');
        /* @var $addToCart \Magium\Magento\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleProductToCartFromCategoryPage();
    }

    public function testSimpleAddToCartWithSpecifiedCategoryAndProduct()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $addToCart = $this->getAction('Cart\AddItemToCart');
        /* @var $addToCart \Magium\Magento\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleProductToCartFromCategoryPage('Accessories/Eyewear', '//a[@title="Aviator Sunglasses"]/../descendant::button');
    }

    public function testAddSimpleItemToCartFromProductPageWithDefaults()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $this->getLogger()->info('Opening page ' . $theme->getBaseUrl());
        $addToCart = $this->getAction('Cart\AddItemToCart');
        /* @var $addToCart \Magium\Magento\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleItemToCartFromProductPage();

    }


    public function testAddSimpleItemToCartFromProductPageWithSpecifiedCategoryAndProduct()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $this->getLogger()->info('Opening page ' . $theme->getBaseUrl());
        $addToCart = $this->getAction('Cart\AddItemToCart');
        /* @var $addToCart \Magium\Magento\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleItemToCartFromProductPage('//a[@title="Aviator Sunglasses"]', 'Accessories/Eyewear');

    }

}