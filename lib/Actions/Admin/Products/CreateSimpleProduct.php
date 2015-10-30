<?php

namespace Magium\Actions\Admin\Products;

use Magium\WebDriver\WebDriver;

class CreateSimpleProduct
{

    protected $webdriver;

    public function __construct(
        WebDriver $webdriver
    ) {
        $this->webdriver = $webdriver;
    }

}