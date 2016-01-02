<?php

namespace Magium;

use Zend\I18n\Translator\Translator;

abstract class AbstractConfigurableElement
{

    protected $translator;

    public function __construct(Translator $translator, $configurationFile = null)
    {
        $this->translator = $translator;
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
                    return;
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

    public function translate($translate)
    {
        if (is_array($translate)) {
            foreach ($translate as $key => $value) {
                $value = $this->translate($value);
                $translate[$key] = $value;
            }
        } else {
            $results = [];
            preg_match_all('/\{\{([^\}]+)\}\}/', $translate, $results);
            array_shift($results);


            foreach ($results as $result) {
                if (is_array($result)) {
                    while (($part = array_shift($result)) !== null) {
                        $translate = $this->translatePart($translate, $part);
                    }
                } else {
                    $translate = $this->translatePart($translate, $result);
                }
            }
        }
        return $translate;
    }

    protected function translatePart($translate, $result)
    {
        $newResult = $this->translator->translate($result);
        $translate = str_replace('{{' . $result . '}}', $newResult, $translate);
        return $translate;
    }

}