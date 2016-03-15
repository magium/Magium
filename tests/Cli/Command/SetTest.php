<?php

namespace Tests\Magium\Cli\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Tests\Magium\Cli\AbstractCliTest;
use Zend\Config\Reader\Json;

class SetTest extends AbstractCliTest
{

    public function testExecute()
    {
        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $command = $application->find('config:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'   => $command->getName(),
            'name'      => 'booger',
            'value'     => 'boogee'
        ]);
        $commandTester->execute([
            'command'   => $command->getName(),
            'name'      => 'booger2',
            'value'     => 'boogee2'
        ]);

        $this->assertFileExists($this->getCliConfigFilename());
        $json = json_decode(file_get_contents($this->getCliConfigFilename()), true);

        $this->assertArrayHasKey('config', $json);
        $this->assertArrayHasKey('booger2', $json['config']);
        $this->assertEquals('boogee2', $json['config']['booger2']);

    }

}