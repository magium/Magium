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

class SetElementValue extends Command implements ConfigurationPathInterface
{

    protected $path;

    public function setPath($path)
    {
        $this->path = $path;
    }

    protected function configure()
    {
        $this->setName('element:set');
        $this->setDescription('Modifies a property value for a configurable element');
        $this->addArgument('class', InputArgument::REQUIRED, 'Need the full name of the class, including namespace');
        $this->addArgument('property', InputArgument::REQUIRED, 'The name of the propery to set (this is not validated and is case sensitive)');
        $this->addArgument('value', InputArgument::REQUIRED, 'Need the value of the setting');
        $this->addOption('json', null, InputOption::VALUE_NONE, 'If set, this will filter the value through json_decode().');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists($this->path . '/magium.json')) {
            throw new NotFoundException('Configuration file not found.  Please execute magium:init.');
        }
        $reader = new \Zend\Config\Reader\Json();
        $config = new Config($reader->fromFile($this->path . '/magium.json'), true);

        $class = $input->getArgument('class');
        $property = $input->getArgument('property');
        $value = $input->getArgument('value');

        if ($input->getOption('json')) {
            $value = json_decode($value);
        }
        if (!$config->magium) {
            $config->magium = [];
        }
        if (!class_exists($class)) {
            throw new NotFoundException('Could not find class: ' . $class . '.  You might need to escape blackslashes (\\\\)');
        }
        $class = strtolower($class);
        $s = $config->magium;
        if (!isset($s[$class])) {
            $s[$class] = [];
        }

        $s[$class]->$property = $value;

        $writer = new Json();
        $writer->toFile($this->path . '/magium.json', $config);
        $output->writeln(sprintf('Wrote value %s for "%s:%s" to %s/magium.json', $value, $class, $property, $this->path));
    }

}

