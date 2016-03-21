<?php

namespace Magium\Util\Configuration;

class EnvironmentConfigurationReader
{

    public function configure(ConfigurableObjectInterface $config)
    {
        $variablePrefix = 'MAGIUM_' . str_replace('\\', '_', strtoupper(get_class($config))) . '_';

        foreach ($_ENV as $key => $value) {
            if (strpos($key, $variablePrefix) === 0) {
                $property = substr($key, strlen($variablePrefix));
                $config->set($property, $value);
            }
        }
    }

}