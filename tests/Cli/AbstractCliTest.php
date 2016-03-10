<?php

namespace Tests\Magium\Cli;

use Magium\Cli\CommandLoader;
use Symfony\Component\Console\Application;

abstract class AbstractCliTest extends \PHPUnit_Framework_TestCase
{

    protected $baseDir;

    protected function setUp()
    {
        parent::setUp();
        $this->baseDir = sys_get_temp_dir();
    }

    protected function getCliConfigFilename()
    {
        return $this->baseDir . '/magium.json';
    }

    protected function getConfiguredApplication()
    {
        $application = new Application();
        $loader = new CommandLoader($application, $this->baseDir);
        $loader->load();
        return $application;
    }

    protected function tearDown()
    {
        if (file_exists($this->getCliConfigFilename())) {
            unlink($this->getCliConfigFilename());
        }
        parent::tearDown();
    }

}