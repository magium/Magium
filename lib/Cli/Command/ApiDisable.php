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

class ApiDisable extends AbstractApiEnablement
{
    function getApiName()
    {
        return 'disable';
    }

    function getApiDescription()
    {
        return 'Disables API integration.  See http://www.magiumlib.com/ for more information.';
    }

    function getValue()
    {
        return 0;
    }


}