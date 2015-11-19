<?php

namespace Magium\Extractors;

use Magium\AbstractTestCase;
use Magium\WebDriver\WebDriver;

abstract class AbstractExtractor
{

    protected $values = [];
    protected $webDriver;
    protected $testCase;

    /**
     * AbstractExtractor constructor.
     *
     * Override this if you have other dependencies.  This is done as a convenience
     *
     * @param WebDriver $webDriver
     * @param AbstractTestCase $testCase
     */

    public function __construct(
        WebDriver           $webDriver,
        AbstractTestCase    $testCase
    )
    {
        $this->webDriver        = $webDriver;
        $this->testCase         = $testCase;
    }

    public function getValue($id)
    {
        if (isset($this->values[$id])) {
            return $this->values[$id];
        }
        return null;
    }

    abstract function extract();
}