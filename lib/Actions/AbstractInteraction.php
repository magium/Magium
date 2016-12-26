<?php

namespace Magium\Actions;

use Magium\WebDriver\WebDriver;

abstract class AbstractInteraction implements ConfigurableActionInterface
{

    protected $webDriver;

    public function __construct(
        WebDriver $webDriver
    )
    {
        $this->webDriver = $webDriver;
    }

}
