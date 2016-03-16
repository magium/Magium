<?php

namespace Magium\Cli\Command;

use Magium\InvalidConfigurationException;
use Magium\NotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListElements extends Command
{
    protected static $dirs = [];

    public static function addDirectory($dir, $namespace)
    {
        if ($dir != realpath($dir)) {
            throw new InvalidConfigurationException('Path not found.  Please ensure that you pass the realpath() in: ' . $dir);
        }
        self::$dirs[$dir] = $namespace;
    }

    protected function configure()
    {
        $this->setName('element:list');
        $this->setDescription('Extracts all of the AbstractConfigurableElement-based objects');
        $this->setHelp(<<<HELP
This command will traverse all known directories, or a specific directory, and return a list of all the classes found that extend Magium\\AbstractConfigurableElement in some way.

It can be used in conjunction with the magium:element:get to narrow down exactly which configuration value you are looking for.
 
There are two modes of operation to be aware of.
 
1. Argument mode

If you run with the "directory" and "namespace" arguments (if one is provided both are required) then this command will recursively descend into this directory and find all configurable elements.

2. Argument-less mode

Running without the options will cause the command to search through all registered paths.

An individual module is responsible to register its paths and does so by creating an autoload register.php file which contains at least the following code

<?php

Magium\Cli\Command\ListElements::addDirectory('my test dir', 'my\\test\\namespace');

Again, all classes must be defined in PSR-4-autoload format.  Custom autoloaders may be used, but the PSR-4 format is there to deduce the name of the class based on the file name.

HELP
);
        $this->addArgument(
            'directory',
            InputArgument::OPTIONAL,
            'The name of the directory to traverse'
        );

        $this->addArgument(
            'namespace',
            InputArgument::OPTIONAL,
            'The psr-4 base namespace of the directory.  Required if <directory> is used'
        );
        $this->addArgument(
            'filter',
            InputArgument::OPTIONAL,
            'A stripos()-compatible filter'
        );

        $this->addOption(
            'escape',
            '--esc',
            InputOption::VALUE_NONE,
            'Set if you want the namespace output escaped (useful for copy and paste)'
        );

        self::addDirectory(realpath(__DIR__ . '/../..'), 'Magium');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument('directory') || $input->getArgument('namespace')) {
            if (!$input->getArgument('directory') || !$input->getArgument('namespace')) {
                throw new NotFoundException('Missing either the directory or namespace argument.  If one is used, then both are required');
            }
        }

        $paths = [];
        if ($input->getArgument('directory') || $input->getArgument('namespace')) {
            $paths = $this->traverseDirectory(realpath($input->getArgument('directory')), $input->getArgument('namespace'));
        } else {
            foreach (self::$dirs as $dir => $namespace) {
                $result = $this->traverseDirectory($dir, $namespace);
                $paths = array_merge($paths, $result);
            }
        }

        sort($paths);
        $filter = $input->getArgument('filter');
        if (count($paths) > 0 ) {
            $output->writeln('Classes found: ');
            $escape = $input->getOption('escape');

            foreach ($paths as $path) {
                if ($escape) {
                    $path = str_replace('\\', '\\\\', $path);
                }
                if ($filter && stripos($path, $filter) !== false) {
                    $output->writeln("\t" . $path);
                }
            }
        } else {
            $output->writeln('No classes found.  If you were expecting to find some you might need to escape your namespace separators');
            $output->writeln('e.g.  Namespace\Class should be written as Namespace\\\\Class');
        }
    }

    /**
     * @param $dir
     * @param $namespace
     * @return array
     */

    protected function traverseDirectory($dir, $namespace)
    {
        $classes = [];
        $dirIterator = new \RecursiveDirectoryIterator($dir);
        $iterator = new \RecursiveIteratorIterator($dirIterator);
        $phpFiles = new \RegexIterator($iterator, '/.*\.php$/', \RegexIterator::GET_MATCH);
        foreach ($phpFiles as $file) {
            if (is_array($file)) {
                $file = array_shift($file);
            }
            if (strpos($file, $dir) === 0) {
                $eCls = explode('\\', $namespace);
                $cls = substr($file, strlen($dir), -4);
                $matches = null;
                preg_match_all('/(\w*)/', $cls, $matches);
                if ($matches) {
                    foreach ($matches[0] as $match) {
                        if ($match) {
                            $eCls[] = $match;
                        }
                    }
                    $class = implode('\\', $eCls);
                    if (class_exists($class) && is_subclass_of($class, 'Magium\AbstractConfigurableElement')) {
                        $classes [] = $class;
                    }
                }

            }
        }
        return $classes;
    }
    
}