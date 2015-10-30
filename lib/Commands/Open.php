<?php

namespace Magium\Commands;

use Magium\WebDriver\WebDriver;
class Open
{
    protected $webdriver;
    
    public function __construct(WebDriver $webdriver)
    {
        $this->webdriver = $webdriver;
    }
    
    public function open($url)
    {
        $this->webdriver->get($url);
    }
    
}