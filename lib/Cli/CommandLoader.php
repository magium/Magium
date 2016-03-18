<?php

namespace Magium\Cli;

use Magium\NotFoundException;
use Symfony\Component\Console\Command\Command;

class CommandLoader
{
    protected $application;
    protected $config;

    protected static $dirs = [];
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
        self::addCommandDir('Magium\Cli\Command', __DIR__ . '/Command');

        foreach (self::$dirs as $namespace => $dir) {
            $files = glob($dir . '/*.php');

            foreach ($files as $file) {
                $class = substr(basename($file), 0, -4);
                $className = $namespace . '\\' . $class;
                if (class_exists($className)) {
                    $reflection = new \ReflectionClass($className);
                    if (!$reflection->isInstantiable()) {
                        continue;
                    }
                    $object = new $className();
                    if ($object instanceof ConfigurationPathInterface) {
                        $object->setPath($this->config);
                    }
                    if ($object instanceof Command) {
                        self::addCommand($object);
                    }
                }
            }

        }


        foreach (self::$commands as $command) {
            $this->application->add($command);
        }
    }

    public static function addCommandDir($namespacePrefix, $dir)
    {
        self::$dirs[$namespacePrefix] = $dir;
    }

    public static function addCommand(Command $command)
    {
        self::$commands[] = $command;
    }

}