<?php

namespace Tests\Magium\Cli\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Tests\Magium\Cli\AbstractCliTest;
use Zend\Config\Reader\Json;

class SetElementValueTest extends AbstractCliTest
{

    public function testAddConfigurationElementValue()
    {
        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $command = $application->find('element:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'   => $command->getName(),
            'class'      => 'Magium\\TestCaseConfiguration',
            'property'     => 'booger',
            'value'     => 'boogee'
        ]);
        $commandTester->execute([
            'command'   => $command->getName(),
            'class'      => 'Magium\\TestCaseConfiguration',
            'property'     => 'booger2',
            'value'     => 'boogee2'
        ]);

        $this->assertFileExists($this->getCliConfigFilename());
        $json = json_decode(file_get_contents($this->getCliConfigFilename()), true);

        $this->assertArrayHasKey('magium', $json);
        $this->assertArrayHasKey('magium\\testcaseconfiguration', $json['magium']);
        $this->assertArrayHasKey('booger', $json['magium']['magium\\testcaseconfiguration']);
        $this->assertEquals('boogee', $json['magium']['magium\\testcaseconfiguration']['booger']);
        $this->assertArrayHasKey('booger2', $json['magium']['magium\\testcaseconfiguration']);
        $this->assertEquals('boogee2', $json['magium']['magium\\testcaseconfiguration']['booger2']);

    }

}