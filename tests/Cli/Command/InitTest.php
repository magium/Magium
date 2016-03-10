<?php

namespace Tests\Magium\Cli\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Tests\Magium\Cli\AbstractCliTest;

class InitTest extends AbstractCliTest
{

    public function testInit()
    {
        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $this->assertFileExists($this->getCliConfigFilename());

    }

}