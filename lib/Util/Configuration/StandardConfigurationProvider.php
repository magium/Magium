<?php

namespace Magium\Util\Configuration;

class StandardConfigurationProvider
{
    protected $configurationFile;

    public function __construct($configurationFile = null)
    {
        $this->configurationFile = $configurationFile;
    }

    public function configureObject(ConfigurableObjectInterface $obj)
    {
// TODO not sure if this is the best way.  Perhaps some kind of test configuration
        if ($this->configurationFile === null) {
            $this->configurationFile = get_class($obj) . '.php';
            $this->configurationFile = str_replace('\\', DIRECTORY_SEPARATOR, $this->configurationFile);
        }

        $count = 0;
        $path = realpath(__DIR__ . '/../');

        while ($count++ < 5) {
            $filename = "{$path}/configuration";
            if (is_dir($filename)) {
                $filename .= "/{$this->configurationFile}";
                if (file_exists($filename)) {
                    include $filename;
                    break;
                }
            }
            $path .= '/../';
            $path = realpath($path); // More for debugging clarity.
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