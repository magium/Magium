<?php

namespace Magium\Cli\Command;

use Magium\Cli\ConfigurationPathInterface;
use Magium\ExistsException;
use Magium\NotAccessibleException;
use Magium\NotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Init extends Command implements ConfigurationPathInterface
{

    protected $path;

    public function setPath($path)
    {
        $this->path = $path;
    }

    protected function configure()
    {
        $this->setName('magium:init');
        $this->setDescription('Creates the magium.json config file in the specified project root directory.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $this->path;
        if ($path && $path[0] != '/' && $path[1] != ':') {
            $path = getcwd() . '/' . $path;
        }
        $calcPath = $path;
        $path = realpath($path);
        if ($path === false) {
            throw new NotFoundException('Path not found: ' . $calcPath);
        }
        if (!is_writable($path)) {
            throw new NotAccessibleException('Could not write to path: ' . $calcPath);
        }
        $fullPath = $path . '/magium.json';
        if (file_exists($fullPath)) {
            throw new ExistsException('The file exists already.  Will not overwrite: ' . $fullPath);
        }
        file_put_contents($fullPath, '{}');
        $output->writeln('Created empty JSON configuration file: ' . realpath($fullPath));

    }

}