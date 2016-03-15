<?php

namespace Tests\Magium\Cli\Command;

use Symfony\Component\Console\Tester\CommandTester;

class UnsetElementValueTest extends SetElementValueTest
{

    public function testAddConfigurationElementValue()
    {
        return; // Not tested here, but we use it in other tests
    }

    public function testEntireElementIsRemoved()
    {
        parent::testAddConfigurationElementValue();

        $application = $this->getConfiguredApplication();

        $command = $application->find('element:unset');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'   => $command->getName(),
            'class'      => 'Magium\\TestCaseConfiguration'
        ]);

        $this->assertFileExists($this->getCliConfigFilename());
        $json = json_decode(file_get_contents($this->getCliConfigFilename()), true);

        $this->assertArrayHasKey('magium', $json);
        $this->assertArrayNotHasKey('magium\\testcaseconfiguration', $json['magium']);
    }

    public function testOnlyElementPropertyIsRemoved()
    {
        parent::testAddConfigurationElementValue();

        $application = $this->getConfiguredApplication();

        $command = $application->find('element:unset');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'   => $command->getName(),
            'class'      => 'Magium\\TestCaseConfiguration',
            'property'  => 'booger'
        ]);

        $this->assertFileExists($this->getCliConfigFilename());
        $json = json_decode(file_get_contents($this->getCliConfigFilename()), true);

        $this->assertArrayHasKey('magium', $json);
        $this->assertArrayHasKey('magium\\testcaseconfiguration', $json['magium']);
        $this->assertArrayHasKey('booger2', $json['magium']['magium\\testcaseconfiguration']);
        $this->assertArrayNotHasKey('booger', $json['magium']['magium\\testcaseconfiguration']);
    }

}