<?php

namespace Magium\Util\Translator;

use Zend\I18n\Translator\Loader\PhpMemoryArray;

class Translator extends \Zend\I18n\Translator\Translator
{
    const SERVICE_NAME = 'magium';

    protected $csvFiles = [];

    protected $loader;

    protected $skipServiceBuild = false;

    /**
     * @param boolean $skipServiceBuild
     */
    public function setSkipServiceBuild($skipServiceBuild)
    {
        $this->skipServiceBuild = $skipServiceBuild;
    }

    public function addTranslationCsvFile($file, $locale)
    {
        if (!isset($this->csvFiles[$locale])) {
            $this->csvFiles[$locale] = [];
        }
        $this->csvFiles[$locale][] = $file;
    }

    public function buildTranslationService()
    {
        if ($this->skipServiceBuild || $this->loader instanceof PhpMemoryArray) {
            return;
        }
        $translation = [
            'default'   => [
                'en_US' => []
            ]
        ];
        foreach ($this->csvFiles as $locale => $fileList) {
            $translation['default'][$locale] = [];
            foreach ($fileList as $file) {
                if (file_exists($file)) {
                    $fh = fopen($file, 'r');
                    while ($parts = fgetcsv($fh)) {
                        $translation['default'][$locale][$parts[0]] = $parts[1];
                    }
                    fclose($fh);
                }
            }
        }
        $this->loader = new PhpMemoryArray($translation);
        $this->getPluginManager()->setService(self::SERVICE_NAME, $this->loader);
        $this->addRemoteTranslations(self::SERVICE_NAME);
    }

    public function translate($message, $textDomain = 'default', $locale = null)
    {
        $this->buildTranslationService();
        return parent::translate($message, $textDomain, $locale);
    }


    public function translatePlaceholders($message, $textDomain = 'default', $locale = null)
    {
        $this->buildTranslationService();
        if (is_array($message)) {
            foreach ($message as $key => $value) {
                $value = $this->translatePlaceholders($value);
                $message[$key] = $value;
            }
        } else {
            $results = [];
            preg_match_all('/\{\{([^\}]+)\}\}/', $message, $results);
            array_shift($results);

            foreach ($results as $result) {
                if (is_array($result)) {
                    while (($part = array_shift($result)) !== null) {
                        $message = $this->translatePart($message, $part, $textDomain, $locale);
                    }
                } else {
                    $message = $this->translatePart($message, $result, $textDomain, $locale);
                }
            }
        }
        return $message;
    }


    protected function translatePart($translate, $result, $textDomain, $locale)
    {
        $newResult = parent::translate($result, $textDomain, $locale);
        $translate = str_replace('{{' . $result . '}}', $newResult, $translate);
        return $translate;
    }
}