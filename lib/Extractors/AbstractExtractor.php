<?php

namespace Magium\Extractors;

use Magium\AbstractTestCase;
use Magium\Themes\ThemeConfigurationInterface;
use Magium\WebDriver\WebDriver;

abstract class AbstractExtractor implements ExtractorInterface
{

    protected $values = [];
    protected $webDriver;
    protected $testCase;
    protected $theme;

    /**
     * AbstractExtractor constructor.
     *
     * Override this if you have other dependencies.  This is done as a convenience.
     *
     * This may change as I'm not quite sure of the best way to handle theme classes at the moment.
     *
     * @param WebDriver $webDriver
     * @param AbstractTestCase $testCase
     * @param ThemeConfigurationInterface $theme
     */

    public function __construct(
        WebDriver           $webDriver,
        AbstractTestCase    $testCase,
        ThemeConfigurationInterface $theme
    )
    {
        $this->webDriver        = $webDriver;
        $this->testCase         = $testCase;
        $this->theme            = $theme;
    }

    public function getValue($id)
    {
        if (isset($this->values[$id])) {
            return $this->values[$id];
        }
        return null;
    }

}