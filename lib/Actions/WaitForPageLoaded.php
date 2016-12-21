<?php

namespace Magium\Actions;

use Facebook\WebDriver\WebDriverElement;
use Magium\Themes\ThemeConfigurationInterface;
use Magium\WebDriver\ExpectedCondition;
use Magium\WebDriver\WebDriver;

class WaitForPageLoaded implements StaticActionInterface
{
    const ACTION = 'WaitForPageLoaded';

    protected $webDriver;
    protected $theme;

    public function __construct(
        WebDriver $webDriver,
        ThemeConfigurationInterface $themeConfiguration
    )
    {
        $this->webDriver = $webDriver;
        $this->theme = $themeConfiguration;
    }

    public function execute(WebDriverElement $testElement = null)
    {
        if ($testElement instanceof WebDriverElement) {
            $this->webDriver->wait()->until(ExpectedCondition::elementRemoved($testElement));
        }

        $this->webDriver->wait()->until(ExpectedCondition::elementExists($this->theme->getGuaranteedPageLoadedElementDisplayedXpath(), WebDriver::BY_XPATH));
        $element = $this->webDriver->byXpath($this->theme->getGuaranteedPageLoadedElementDisplayedXpath());
        $this->webDriver->wait()->until(ExpectedCondition::visibilityOf($element));
    }

}
