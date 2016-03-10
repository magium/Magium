<?php

namespace Magium\Cli;

use Magium\NotFoundException;
use Symfony\Component\Console\Command\Command;

class CommandLoader
{
    protected $application;
    protected $config;

    protected static $commands = [];

    public function __construct(
        \Symfony\Component\Console\Application $application,
        $configDir
    )
    {
        if (!is_dir($configDir)) {
            throw new NotFoundException('Configuration dir not found: ' . $configDir);
        }
        $this->config = $configDir;

        $this->application = $application;
    }

    public function load()
    {
        $files = glob(__DIR__ . '/Command/*.php');

        foreach ($files as $file) {
            $class = substr(basename($file), 0, -4);
            $className = __NAMESPACE__ . '\\Command\\' . $class;
            if (class_exists($className)) {
                $object = new $className();
                if ($object instanceof ConfigurationPathInterface) {
                    $object->setPath($this->config);
                }
                if ($object instanceof Command) {
                    self::addCommand($object);
                }
            }
        }

        foreach (self::$commands as $command) {
            $this->application->add($command);
        }
    }

    public static function addCommand(Command $command)
    {
        self::$commands[] = $command;
    }

}