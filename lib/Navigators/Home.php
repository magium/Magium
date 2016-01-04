<?php

namespace Magium\Navigators;

use Magium\Themes\BaseThemeInterface;
use Magium\WebDriver\WebDriver;

class Home
{

    const NAVIGATOR = 'HOME';

    protected $webDriver;
    protected $baseTheme;

    /**
     * Home constructor.
     * @param $baseTheme
     * @param $webDriver
     */
    public function __construct(BaseThemeInterface $baseTheme, WebDriver $webDriver)
    {
        $this->baseTheme = $baseTheme;
        $this->webDriver = $webDriver;
    }


    public function navigateTo()
    {
        $this->webDriver->byXpath($this->baseTheme->getHomeXpath());
    }

}