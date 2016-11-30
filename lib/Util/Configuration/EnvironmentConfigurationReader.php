<?php

namespace Magium\Util\Configuration;

class EnvironmentConfigurationReader extends AbstractConfigurationReader

{

    public function configure(ConfigurableObjectInterface $config)
    {
        $classes = $this->introspectClass($config);

        foreach ($classes as $class) {

            $variablePrefix = 'MAGIUM_' . str_replace('\\', '_', strtoupper($class)) . '_';

            // Fixes https://github.com/magium/Magium/issues/114
            $props = array_merge($_SERVER, $_ENV);

            foreach ($props as $key => $value) {
                if (strpos($key, $variablePrefix) === 0) {
                    $property = substr($key, strlen($variablePrefix));
                    $config->set($property, $value);
                }
            }
        }
    }

}
