<?php

namespace Magium;

abstract class AbstractConfigurableElement
{

    public function __construct($configurationFile = null)
    {
        // TODO not sure if this is the best way.  Perhaps some kind of test configuration
        if ($configurationFile === null) {
            $configurationFile = get_class($this) . '.php';
            $configurationFile = str_replace('\\', DIRECTORY_SEPARATOR, $configurationFile);
        }

        $count = 0;
        $path = '../';

        while ($count++ < 5) {
            $filename = "{$path}/configuration";
            if (is_dir($filename)) {
                $filename .= "/{$configurationFile}";
                if (file_exists($filename)) {
                    include $filename;
                    return;
                }
            }
            $path .= '../';
        }
    }

}