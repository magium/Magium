<?php

namespace Checkout;

use Magium\AbstractTestCase;

class AddItemToCartTest extends AbstractTestCase
{

    public function testSimpleAddToCartWithDefaults()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $addToCart = $this->get('Magium\Actions\Cart\AddItemToCart');
        /* @var $addToCart \Magium\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleProductToCart();
    }

    public function testSimpleAddToCartWithSpecifiedCategoryAndProduct()
    {
        $theme = $this->getTheme();
        $this->commandOpen($theme->getBaseUrl());
        $addToCart = $this->get('Magium\Actions\Cart\AddItemToCart');
        /* @var $addToCart \Magium\Actions\Cart\AddItemToCart */

        $addToCart->addSimpleProductToCart('Accessories/Eyewear', '//a[@title="Aviator Sunglasses"]/../descendant::button');
    }

}