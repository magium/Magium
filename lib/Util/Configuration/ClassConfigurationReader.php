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
            $path = realpath(__DIR__ . '/../../../../..'); // Get out of the Magium directories
            $count = 0;
            while ($count++ < 10) {
                $filename = "{$path}/configuration";
                $realpath = realpath($filename);
                if ($realpath !== false && is_dir($realpath)) {
                    // The equality check is due to case-insensitive file systems *ahem* Windows and OS X HFS+
                    $directories = glob(realpath($filename.'/../').'/*', GLOB_ONLYDIR);
                    foreach ($directories as $directory) {
                        $parts = explode(DIRECTORY_SEPARATOR, $directory);
                        $lastPart = array_pop($parts);
                        if ($lastPart == 'configuration') {
                            $configurationDir = $realpath;
                            break;
                        }
                    }
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