<?php

namespace Magium\Cli\Command;

use Magium\Cli\Command\Test\TestSkeleton;
use Magium\InvalidConfigurationException;
use Magium\NotFoundException;
use Magium\Util\Api\ApiConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ApiPing extends Command
{

    protected function configure()
    {
        $this->setName('api:ping');
        $this->setDescription('Pings the API ping endpoint to ensure that the key works');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $test = new TestSkeleton();
        $test->configureDi();
        $api = $test->get('Magium\Util\Api\ApiConfiguration');
        $api->setEnabled(true); // Gotta force this for this test
        $request = $test->get('Magium\Util\Api\Request');
        /* @var $request \Magium\Util\Api\Request */
        $response = $request->fetch('/api/ping');
        $output->writeln($response);
    }

    
}