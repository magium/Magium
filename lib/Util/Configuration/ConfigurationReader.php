<?php

namespace Magium\Util\Configuration;

class ConfigurationReader extends AbstractConfigurationReader
{

    protected $baseDir;

    protected $config = null;

    public function __construct(
        $baseDir = null
    )
    {
        $this->baseDir = $baseDir;
    }

    public function configure(ConfigurableObjectInterface $config)
    {
        if ($this->config === null) {
            $file = 'magium.json';
            $dir = $this->baseDir;
            if ($dir === null) {
                $dir = __DIR__;
            }
            $count = 0;
            while ($count++ < 10) {
                $filePath = $dir . '/' . $file;
                if (file_exists($filePath)) {
                    $this->config = json_decode(file_get_contents($filePath), true);
                    break;
                }
                $dir = realpath($dir . '/..');
            }
        }

        if ($this->config === null) {
            $this->config = false;
        }

        if ($this->config) {
            $classes = $this->introspectClass($config);
            foreach ($classes as $class) {
                $className = strtolower($class);
                if (isset($this->config['magium'][$className])) {
                    foreach ($this->config['magium'][$className] as $property => $value) {
                        $config->set($property, $value);
                    }
                }
            }
        }

    }

}
