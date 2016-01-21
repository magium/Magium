<?php

namespace Magium\Themes;

use Magium\AbstractTestCase;

interface BaseThemeInterface extends ThemeConfigurationInterface
{
    public function getHomeXpath();

    public function configure(AbstractTestCase $testCase);

}