<?php

namespace Magium\Util\Configuration;

class ClassConfigurationReader
{

    protected $configurationDir;
    protected $object;

    public function __construct(
        $configurationDir = null
    )
    {
        $this->configurationDir = $configurationDir;
    }

    /**
     * @param null $configurationDir
     */
    public function setConfigurationDir($configurationDir)
    {
        $this->configurationDir = $configurationDir;
    }


    public function __set($name, $value)
    {
        $this->object->$name = $value;
    }

    public function __call($method, $args)
    {
        call_user_func_array([$this->object, $method], $args);
    }


    public function configure(ConfigurableObjectInterface $config)
    {
        $this->object = $config;
        $configurationDir = $this->configurationDir;
        if ($configurationDir === null) {
            $path = realpath(__DIR__ . '/../');
            $count = 0;
            while ($count++ < 10) {
                $filename = "{$path}/configuration";
                $realpath = realpath($filename);
                $parts = explode(DIRECTORY_SEPARATOR, $realpath);
                $lastPart = array_pop($parts);
                // The equality check is due to case-insensitive file systems *ahem* Windows
                if ($lastPart == 'configuration' && is_dir($realpath)) {
                    $configurationDir = $realpath;
                    break;
                }
                $path .= '/../';
                $path = realpath($path); // More for debugging clarity.
            }
        }

        if (!is_dir($configurationDir)) return;

        $configurationFile = get_class($config) . '.php';
        $configurationFile = $configurationDir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $configurationFile);

        if (file_exists($configurationFile)) {
            include $configurationFile;
        }

    }

}