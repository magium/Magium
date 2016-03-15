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

class UnsetElementValue extends Command implements ConfigurationPathInterface
{

    protected $path;

    public function setPath($path)
    {
        $this->path = $path;
    }

    protected function configure()
    {
        $this->setName('element:unset');
        $this->setDescription('Removes an abstract configurable element\'s property');
        $this->addArgument('class', InputArgument::REQUIRED, 'The name of the class');
        $this->addArgument('property', InputArgument::OPTIONAL, 'The name of the property to remove.  Omitting will remove the entire class setting');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!file_exists($this->path . '/magium.json')) {
            throw new NotFoundException('Configuration file not found.  Please execute magium:init.');
        }
        $reader = new \Zend\Config\Reader\Json();
        $config = new Config($reader->fromFile($this->path . '/magium.json'), true);

        $name = strtolower($input->getArgument('class'));

        if (isset($config->magium->$name)) {
            $property = $input->getArgument('property');
            if ($property && isset($config->magium->$name->$property)) {
                unset($config->magium->$name->$property);
                $output->writeln(sprintf('Removed the property %s in %s in %s/magium.json', $property, $input->getArgument('class'), $this->path));
                if (count($config->magium->$name) == 0) {
                    unset($config->magium->$name);
                    $output->writeln(sprintf('Removed empty settings for %s in %s/magium.json', $input->getArgument('class'), $this->path));
                }
            } elseif ($property === null) {
                unset($config->magium->$name);
                $output->writeln(sprintf('Removed all %s settings in %s/magium.json', $input->getArgument('class'), $this->path));
            } else {
                $output->writeln(sprintf('Property %s in %s not found in %s/magium.json', $property, $input->getArgument('class'), $this->path));
            }

        } else {
            $output->writeln(sprintf('%s was not found in %s/magium.json', $input->getArgument('class'), $this->path));
        }

        $writer = new Json();
        $writer->toFile($this->path . '/magium.json', $config);

    }



}

