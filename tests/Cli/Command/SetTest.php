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

        $command = $application->find('magium:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'   => $command->getName(),
            'name'      => 'booger',
            'value'     => 'boogee'
        ]);

        $this->assertFileExists($this->getCliConfigFilename());
        $json = json_decode(file_get_contents($this->getCliConfigFilename()), true);

        $this->assertArrayHasKey('config', $json);
        $this->assertArrayHasKey('booger', $json['config']);
        $this->assertEquals('boogee', $json['config']['booger']);

    }

}