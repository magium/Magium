<?php

namespace Tests\Magium\Navigators;

use Magium\AbstractTestCase;
use Magium\Themes\BaseThemeInterface;

class ThemeConfiguration implements BaseThemeInterface
{
    protected $homeXpath = '//body';
    protected $element = '//*';

    public function getHomeXpath()
    {
        return $this->homeXpath;
    }

    public function configure(AbstractTestCase $testCase)
    {
        return $this;
    }

    public function getGuaranteedPageLoadedElementDisplayedXpath()
    {
        return $this->element;
    }

    public function setGuaranteedPageLoadedElementDisplayedXpath($xpath)
    {
        $this->element = $xpath;
    }

}