<?php

namespace Checkout;

use Magium\AbstractTestCase;

class AddItemToCartTest extends AbstractTestCase
{

    public function testSimpleAddToCartWithDefaults()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $this->getLogger()->info('Opening page ' . $theme->getBaseUrl());
        $addToCart = $this->get('Magium\Actions\Cart\AddItemToCart');
        /* @var $addToCart \Magium\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleProductToCartFromCategoryPage();
    }

    public function testSimpleAddToCartWithSpecifiedCategoryAndProduct()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $addToCart = $this->get('Magium\Actions\Cart\AddItemToCart');
        /* @var $addToCart \Magium\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleProductToCartFromCategoryPage('Accessories/Eyewear', '//a[@title="Aviator Sunglasses"]/../descendant::button');
    }

    public function testAddSimpleItemToCartFromProductPageWithDefaults()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $this->getLogger()->info('Opening page ' . $theme->getBaseUrl());
        $addToCart = $this->get('Magium\Actions\Cart\AddItemToCart');
        /* @var $addToCart \Magium\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleItemToCartFromProductPage();

    }


    public function testAddSimpleItemToCartFromProductPageWithSpecifiedCategoryAndProduct()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $this->getLogger()->info('Opening page ' . $theme->getBaseUrl());
        $addToCart = $this->get('Magium\Actions\Cart\AddItemToCart');
        /* @var $addToCart \Magium\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleItemToCartFromProductPage('//a[@title="Aviator Sunglasses"]', 'Accessories/Eyewear');

    }

}