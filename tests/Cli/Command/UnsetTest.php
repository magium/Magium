<?php

namespace Tests\Magium\Cli\Command;

use Symfony\Component\Console\Tester\CommandTester;

class UnsetTest extends SetTest
{

    public function testExecute()
    {
        parent::testExecute();

        $application = $this->getConfiguredApplication();

        $command = $application->find('config:unset');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'   => $command->getName(),
            'name'      => 'booger'
        ]);

        $this->assertFileExists($this->getCliConfigFilename());
        $json = json_decode(file_get_contents($this->getCliConfigFilename()), true);

        $this->assertArrayHasKey('config', $json);
        $this->assertArrayNotHasKey('booger', $json['config']);


    }

}