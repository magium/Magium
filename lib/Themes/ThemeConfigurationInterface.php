<?php

namespace Magium\Themes;

/**
 * At this point in time this interface is only used to help with populating theme information
 *
 * Interface ThemeConfigurationInterface
 * @package Magium\Themes
 */

interface ThemeConfigurationInterface extends ThemeInterface
{

    public function getGuaranteedPageLoadedElementDisplayedXpath();

    public function setGuaranteedPageLoadedElementDisplayedXpath($xpath);

}
