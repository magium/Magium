<?php

namespace Tests\Magium\Cli\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Tests\Magium\Cli\AbstractCliTest;

class WebDriverTest extends AbstractCliTest
{

    public function testWebDriverUrl()
    {
        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $this->assertFileExists($this->getCliConfigFilename());

        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:webdriver');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--url'   => 'http://whatever/'
        ]);
        $this->assertFileExists($this->getCliConfigFilename());
        $json = json_decode(file_get_contents($this->getCliConfigFilename()), true);

        $this->assertArrayHasKey('magium', $json);
        $this->assertArrayHasKey('magium\\testcaseconfiguration', $json['magium']);
        $this->assertArrayHasKey('webDriverRemote', $json['magium']['magium\\testcaseconfiguration']);
        $this->assertEquals('http://whatever/',  $json['magium']['magium\\testcaseconfiguration']['webDriverRemote']);

    }

    public function testCapabilities()
    {
        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $this->assertFileExists($this->getCliConfigFilename());

        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:webdriver');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--capability'   => 'chrome'
        ]);
        $this->assertFileExists($this->getCliConfigFilename());
        $json = json_decode(file_get_contents($this->getCliConfigFilename()), true);

        $this->assertArrayHasKey('magium', $json);
        $this->assertArrayHasKey('magium\\testcaseconfiguration', $json['magium']);
        $this->assertArrayHasKey('capabilities', $json['magium']['magium\\testcaseconfiguration']);
        $this->assertEquals('chrome',  $json['magium']['magium\\testcaseconfiguration']['capabilities']);

    }

    public function testMissingBothThrowsAnError()
    {

        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $this->assertFileExists($this->getCliConfigFilename());

        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:webdriver');
        $commandTester = new CommandTester($command);
        $this->setExpectedException('Magium\InvalidInstructionException');
        $commandTester->execute([]);


    }

}