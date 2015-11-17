<?php

namespace Magium\Magento\Actions\Admin;

use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class WaitForLoadingMask
{

    protected $webDriver;

    public function __construct(
        WebDriver          $webDriver
    )
    {
        $this->webDriver    = $webDriver;
    }

    public function wait()
    {
        $this->webDriver->wait()->until(ExpectedCondition::not(ExpectedCondition::visibilityOf($this->webDriver->byId('loading-mask'))));
    }

}