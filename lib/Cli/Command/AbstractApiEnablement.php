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

abstract class AbstractApiEnablement extends Command
{

    abstract public function getApiName();
    abstract public function getApiDescription();
    abstract public function getValue();

    protected function configure()
    {
        $this->setName('api:' . $this->getApiName());
        $this->setDescription($this->getApiDescription());
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('element:set');
        $input = new ArrayInput([
            'command'   => $command->getName(),
            'class'     => 'Magium\Util\Api\ApiConfiguration',
            'property'  => 'enabled',
            'value'     => $this->getValue(),
        ]);
        $command->run($input, $output);
    }

    
}