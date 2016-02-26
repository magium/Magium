<?php

namespace Magium\Util\Configuration;

class StandardConfigurationProvider
{
    protected $configurationFile;

    /**
     * @var ConfigurableObjectInterface
     */

    protected $object;

    public function __construct($configurationFile = null)
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

    public function configureObject(ConfigurableObjectInterface $obj)
    {
        $this->object = $obj;
        $configurationFile = $this->configurationFile;
        if ($configurationFile === null) {
            $configurationFile = get_class($obj) . '.php';
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

        $variablePrefix = 'MAGIUM_' . str_replace('\\', '_', strtoupper(get_class($obj))) . '_';

        foreach ($_ENV as $key => $value) {
            if (strpos($key, $variablePrefix) === 0) {
                $property = substr($key, strlen($variablePrefix));
                $obj->set($property, $value);
            }
        }
    }

}