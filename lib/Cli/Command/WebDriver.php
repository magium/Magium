<?php

namespace Magium\Cli\Command;

use Magium\InvalidInstructionException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebDriver extends Command
{

    protected function configure()
    {
        $this->setName('magium:webdriver');
        $this->setDescription('Configures the WebDriver settings');
        $this->addOption(
            'url',
            null,
            InputArgument::OPTIONAL,
            'The URL to connect to'
        );
        $this->addOption(
            'capability',
            null,
            InputArgument::OPTIONAL,
            'The browser capability (chrome, firefox, phantomjs, etc.)'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('element:set');

        if (!$input->getOption('url') && !$input->getOption('capability')) {
            throw new InvalidInstructionException('url and/or capability required.  Neither found.');
        }

        $url = $input->getOption('url');
        if ($url) {
            $arguments = [
                'command' => $command->getName(),
                'class'    => 'Magium\\TestCaseConfiguration',
                'property'  => 'webDriverRemote',
                'value'     => $url
            ];
            $webDriverInput = new ArrayInput($arguments);
            $command->run($webDriverInput, $output);
        }

        $capability = $input->getOption('capability');
        if ($capability) {
            $arguments = [
                'command' => $command->getName(),
                'class'    => 'Magium\\TestCaseConfiguration',
                'property'  => 'capabilities',
                'value'     => $capability
            ];
            $webDriverInput = new ArrayInput($arguments);
            $command->run($webDriverInput, $output);
        }
    }

}