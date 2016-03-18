<?php

namespace Magium\Cli\Command;

use Magium\InvalidConfigurationException;
use Magium\NotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ApiSet extends Command
{

    protected function configure()
    {
        $this->setName('api:set');
        $this->setDescription('Sets the API key, secret and (optionally) the hostname for the API');
        $this->addArgument(
            'key',
            InputArgument::REQUIRED,
            'The API key'
        );
        $this->addArgument(
            'secret',
            InputArgument::REQUIRED,
            'The key secret'
        );
        $this->addArgument(
            'hostname',
            InputArgument::OPTIONAL,
            'Changes the hostname'
        );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('element:set');
        $input2 = new ArrayInput([
            'command'   => $command->getName(),
            'class'     => 'Magium\Util\Api\ApiConfiguration',
            'property'  => 'key',
            'value'     => $input->getArgument('key'),
        ]);
        $command->run($input2, $output);

        $input2 = new ArrayInput([
            'command'   => $command->getName(),
            'class'     => 'Magium\Util\Api\ApiConfiguration',
            'property'  => 'secret',
            'value'     => $input->getArgument('secret'),
        ]);
        $command->run($input2, $output);

        if ($input->getArgument('hostname')) {

            $input2 = new ArrayInput([
                'command'   => $command->getName(),
                'class'     => 'Magium\Util\Api\ApiConfiguration',
                'property'  => 'apiHostname',
                'value'     => $input->getArgument('hostname'),
            ]);
            $command->run($input2, $output);
        }

    }

    
}