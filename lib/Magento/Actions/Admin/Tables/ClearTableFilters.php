<?php

namespace Magium\Magento\Actions\Admin\Tables;

use Facebook\WebDriver\WebDriverBy;
use Magium\WebDriver\WebDriver;

class ClearTableFilters
    {

    protected $webDriver;

    public function __construct(
        WebDriver   $webDriver
    )
    {
        $this->webDriver    = $webDriver;
    }

    public function clear()
    {
        $elements = $this->webDriver->findElements(WebDriverBy::xpath('//tr[@class="filter"]/descendant::input[@type="text"]'));
        foreach ($elements as $element) {
            $element->clear();
        }
    }
}