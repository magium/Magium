<?php

namespace Magium\Util\Translator;

class Translator extends \Zend\I18n\Translator\Translator
{
    public function translate($message, $textDomain = 'default', $locale = null)
    {
        if (is_array($message)) {
            foreach ($message as $key => $value) {
                $value = $this->translate($value);
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