<?php

namespace Magium\Cli\Command;

use Magium\Cli\ConfigurationPathInterface;
use Magium\NotFoundException;
use Magium\Util\Configuration\ConfigurationCollector\DefaultPropertyCollector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Config\Config;
use Zend\Config\Writer\Json;

class GetElementValue extends Command implements ConfigurationPathInterface
{

    protected $path;

    public function setPath($path)
    {
        $this->path = $path;
    }

    protected function configure()
    {
        $this->setName('element:get');
        $this->setDescription('Retrieves the default values for a configurable element');
        $this->addArgument('class', InputArgument::REQUIRED, 'Need the full name of the class, including namespace');
        $this->addArgument('filter', InputArgument::OPTIONAL, 'A stripos()-based filter of the properties');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $collector = new DefaultPropertyCollector();
        $class = $input->getArgument('class');
        if (!class_exists($class)) {
            throw new NotFoundException('Could not find the class: ' . $class);
        }
        if (!is_subclass_of($class, 'Magium\AbstractConfigurableElement')) {
            throw new \InvalidArgumentException('Class must be a sub-class of Magium\AbstractConfigurableElement');
        }
        $properties = $collector->extract($class);
        $print = [];
        if ($input->getArgument('filter')) {
            $filter = $input->getArgument('filter');
            foreach ($properties as $property) {
                if (stripos($property->getName(), $filter) !== false) {
                    $print[] = $property;
                }
            }
        } else {
            $print = $properties;
        }

        foreach ($print as $property) {
            $output->writeln('');
            $output->writeln($property->getName());
            $output->writeln("\tDefault Value: " . $property->getDefaultValue());
            if ($property->getDescription()) {
                $output->writeln("\t" . $property->getDescription());
            }
        }
    }
}

