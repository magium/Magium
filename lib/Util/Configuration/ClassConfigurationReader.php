<?php

namespace Magium\Util\Configuration;

class ClassConfigurationReader extends AbstractConfigurationReader
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
        return call_user_func_array([$this->object, $method], $args);
    }


    public function configure(ConfigurableObjectInterface $config)
    {
        $this->object = $config;
        $configurationDir = $this->configurationDir;
        if ($configurationDir === null) {
            $testParts = explode(DIRECTORY_SEPARATOR, realpath(__DIR__.'/../../..'));
            $isVendor = array_pop($testParts) == 'vendor';

            if ($isVendor) {
                $path = realpath(__DIR__ . '/../../../../..'); // Get out of the Magium directories
            } else {
                $path = realpath(__DIR__ . '/../../..');
            }

            $count = 0;
            while ($count++ < 10) {
                $filename = "{$path}/configuration";
                $realpath = realpath($filename);
                if ($realpath !== false && is_dir($realpath)) {
                    // The equality check is due to case-insensitive file systems *ahem* Windows and OS X HFS+
                    $directories = glob(realpath($filename.'/../').'/*', GLOB_ONLYDIR);
                    foreach ($directories as $directory) {
                        $directory = realpath($directory);
                        $parts = explode(DIRECTORY_SEPARATOR, $directory);
                        $lastPart = array_pop($parts);
                        if ($lastPart == 'configuration') {
                            $configurationDir = $realpath;
                            break 2;
                        }
                    }
                }
                $path .= '/../';
                $path = realpath($path); // More for debugging clarity.
            }
        }

        if (!is_dir($configurationDir)) return;

        $classes = $this->introspectClass($config);

        foreach ($classes as $class) {
            $configurationFile = $class . '.php';
            $configurationFile = $configurationDir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $configurationFile);

            if (file_exists($configurationFile)) {
                include $configurationFile;
            }
        }
    }

}
