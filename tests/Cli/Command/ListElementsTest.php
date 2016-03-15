<?php

namespace Tests\Magium\Cli\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Tests\Magium\Cli\AbstractCliTest;
use Zend\Config\Reader\Json;

class ListElementsTest extends AbstractCliTest
{

    public function testMissingNamespaceThrowsException()
    {
        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->setExpectedException('Magium\NotFoundException');
        $command = $application->find('element:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'   => $command->getName(),
            'namespace'      => 'Tests\Magium\Cli\Command\ListElement',
        ]);
    }

    public function testMissingDirectoryThrowsException()
    {
        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->setExpectedException('Magium\NotFoundException');
        $command = $application->find('element:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'   => $command->getName(),
            'directory'      => __DIR__,
        ]);
    }

    public function testTraverseCustomPath()
    {
        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $command = $application->find('element:list');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'   => $command->getName(),
            'namespace'      => 'Tests\Magium\Cli\Command\ListElement',
            'directory'     => realpath(__DIR__ . '/ListElement')
        ]);
    }

}