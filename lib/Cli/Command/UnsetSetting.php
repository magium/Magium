<?php

namespace Magium\Cli\Command;

use Magium\Cli\ConfigurationPathInterface;
use Magium\NotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Config\Config;
use Zend\Config\Writer\Json;

class UnsetSetting extends Command implements ConfigurationPathInterface
{

    protected $path;

    public function setPath($path)
    {
        $this->path = $path;
    }

    protected function configure()
    {
        $this->setName('config:unset');
        $this->setDescription('Removes a setting');
        $this->addArgument('name', InputArgument::REQUIRED, 'Need the name of the setting');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists($this->path . '/magium.json')) {
            throw new NotFoundException('Configuration file not found.  Please execute magium:init.');
        }
        $reader = new \Zend\Config\Reader\Json();
        $config = new Config($reader->fromFile($this->path . '/magium.json'), true);

        $name = $input->getArgument('name');

        if (isset($config->config->$name)) {
            unset($config->config->$name);
        }

        $writer = new Json();
        $writer->toFile($this->path . '/magium.json', $config);
        $output->writeln(sprintf('Removed value for "%s" in %s/magium.json', $name, $this->path));
    }



}

