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

class SetSetting extends Command implements ConfigurationPathInterface
{

    protected $path;

    public function setPath($path)
    {
        $this->path = $path;
    }

    protected function configure()
    {
        $this->setName('config:set');
        $this->setDescription('Modifies a setting');
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the setting');
        $this->addArgument('value', InputArgument::REQUIRED, 'The value of the setting');
        $this->addOption('json', null, InputOption::VALUE_NONE, 'If set, this will filter the value through json_decode().');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists($this->path . '/magium.json')) {
            throw new NotFoundException('Configuration file not found.  Please execute magium:init.');
        }
        $reader = new \Zend\Config\Reader\Json();
        $config = new Config($reader->fromFile($this->path . '/magium.json'), true);

        $name = $input->getArgument('name');
        $value = $input->getArgument('value');
        $output->writeln($value);

        if ($input->getOption('json')) {
            $value = json_decode($value);
        }
        if (!$config->config) {
            $config->config = [];
        }
        $config->config->$name = $value;

        $writer = new Json();
        $writer->toFile($this->path . '/magium.json', $config);
        $output->writeln(sprintf('Wrote value for "%s" to %s/magium.json', $name, $this->path));
    }



}

