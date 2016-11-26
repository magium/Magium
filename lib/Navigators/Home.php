<?php

namespace Magium\Navigators;

use Magium\Actions\WaitForPageLoaded;
use Magium\Themes\BaseThemeInterface;
use Magium\WebDriver\WebDriver;

class Home implements StaticNavigatorInterface
{

    const NAVIGATOR = 'Home';

    protected $webDriver;
    protected $baseTheme;
    protected $loaded;

    /**
     * Home constructor.
     * @param $baseTheme
     * @param $webDriver
     */
    public function __construct(BaseThemeInterface $baseTheme, WebDriver $webDriver, WaitForPageLoaded $loaded)
    {
        $this->baseTheme = $baseTheme;
        $this->webDriver = $webDriver;
        $this->loaded = $loaded;
    }


    public function navigateTo()
    {
        $testElement = $this->webDriver->byXpath('//body');
        $this->webDriver->byXpath($this->baseTheme->getHomeXpath())->click();
        $this->loaded->execute($testElement);
    }

}