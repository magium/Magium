<?php

namespace Tests\Magium\Cli;

use Magium\Cli\CommandLoader;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

class BaseLoaderTest extends AbstractCliTest
{

    public function testCommandAddedViaStaticMethod()
    {
        $command = new TestCommand();
        CommandLoader::addCommand($command);

        $application = $this->getConfiguredApplication();

        self::assertInstanceOf('Tests\Magium\Cli\TestCommand', $application->find('test:test'));
    }


}

class TestCommand extends Command
{

    protected function configure()
    {
        $this->setName('test:test');
        parent::configure();
    }

}