<?php

namespace Magium\Actions;

use Magium\WebDriver\WebDriver;

class Type implements ConfigurableActionInterface
{

    const ACTION = 'Type';

    protected $webDriver;

    public function __construct(
        WebDriver $webDriver
    )
    {
        $this->webDriver = $webDriver;
    }

    public function execute($param)
    {
        $this->webDriver->getKeyboard()->sendKeys($param);
    }

}
