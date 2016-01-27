<?php

namespace Magium;



use Magium\Util\Translator\Translator;
use Magium\Util\Translator\TranslatorAware;

abstract class AbstractConfigurableElement implements TranslatorAware
{
    protected $translator;

    public function __construct($configurationFile = null)
    {
        // TODO not sure if this is the best way.  Perhaps some kind of test configuration
        if ($configurationFile === null) {
            $configurationFile = get_class($this) . '.php';
            $configurationFile = str_replace('\\', DIRECTORY_SEPARATOR, $configurationFile);
        }

        $count = 0;
        $path = realpath(__DIR__ . '/../');

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
            $path = realpath($path); // More for debugging clarity.
        }

        $variablePrefix = 'MAGIUM_' . str_replace('\\', '_', strtoupper(get_class($this))) . '_';

        foreach ($_ENV as $key => $value) {
            if (strpos($key, $variablePrefix) === 0) {
                $property = substr($key, strlen($variablePrefix));
                $this->$property = $value;
            }
        }

    }

    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * This method is used for Unit Testing.  You will probably not need it
     *
     * @return Translator
     */

    public function getTranslator()
    {
        return $this->translator;
    }

    public function translatePlaceholders($translate)
    {
        $newTranslate = $this->translator->translatePlaceholders($translate);
        return $newTranslate;
    }

}