<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;
use Magium\Themes\BaseThemeInterface;

class ThemeSwitchTest extends AbstractTestCase
{
    public function testConfigureIsCalledWhenThemeIsSwitched()
    {
        $this->switchThemeConfiguration('Tests\Magium\AbstractTestCase\ThemeSwitchConfiguration');
        self::assertTrue($this->getTheme()->executed);
    }
}

class ThemeSwitchConfiguration implements BaseThemeInterface
{
    public $executed = false;

    public function getHomeXpath()
    {
        // TODO: Implement getHomeXpath() method.
    }

    public function configure(AbstractTestCase $testCase)
    {
        $this->executed= true;
    }

    public function getGuaranteedPageLoadedElementDisplayedXpath()
    {
        // TODO: Implement getGuaranteedPageLoadedElementDisplayedXpath() method.
    }

    public function setGuaranteedPageLoadedElementDisplayedXpath($xpath)
    {
        // TODO: Implement setGuaranteedPageLoadedElementDisplayedXpath() method.
    }


}