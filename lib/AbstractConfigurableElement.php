<?php

namespace Magium;


use Zend\I18n\Translator\TranslatorAwareTrait;

abstract class AbstractConfigurableElement
{
    use TranslatorAwareTrait;


    public function __construct($configurationFile = null)
    {
        // TODO not sure if this is the best way.  Perhaps some kind of test configuration
        if ($configurationFile === null) {
            $configurationFile = get_class($this) . '.php';
            $configurationFile = str_replace('\\', DIRECTORY_SEPARATOR, $configurationFile);
        }

        $count = 0;
        $path = __DIR__ . '../';

        while ($count++ < 5) {
            $filename = "{$path}/configuration";
            if (is_dir($filename)) {
                $filename .= "/{$configurationFile}";
                if (file_exists($filename)) {
                    include $filename;
                    break;
                }
            }
            $path .= '../';
        }

        $variablePrefix = 'MAGIUM_' . str_replace('\\', '_', strtoupper(get_class($this))) . '_';

        foreach ($_ENV as $key => $value) {
            if (strpos($key, $variablePrefix) === 0) {
                $property = substr($key, strlen($variablePrefix));
                $this->$property = $value;
            }
        }

    }



    public function translatePlaceholders($translate)
    {
        $newTranslate = $this->translator->translatePlaceholders($translate);
        return $newTranslate;
    }

}