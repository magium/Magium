<?php

namespace Magium\Util\Configuration;

class ClassConfigurationReader
{

    protected $configurationFile;
    protected $object;

    public function __construct(
        $configurationFile = null
    )
    {
        $this->configurationFile = $configurationFile;
    }

    /**
     * @param null $configurationFile
     */
    public function setConfigurationFile($configurationFile)
    {
        $this->configurationFile = $configurationFile;
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
        $configurationFile = $this->configurationFile;
        if ($configurationFile === null) {
            $configurationFile = get_class($config) . '.php';
            $configurationFile = str_replace('\\', DIRECTORY_SEPARATOR, $configurationFile);

            $count = 0;
            $path = realpath(__DIR__ . '/../');

            while ($count++ < 10) {
                $filename = "{$path}/configuration";
                if (is_dir($filename)) {
                    $filename .= '/' . $configurationFile;
                    if (file_exists($filename)) {
                        $configurationFile = realpath($filename);
                        break;
                    }
                }
                $path .= '/../';
                $path = realpath($path); // More for debugging clarity.
            }
        }

        if (file_exists($configurationFile)) {
            include $configurationFile;
        }

    }

}