<?php

namespace Magium;

use Magium\Util\Configuration\ConfigurableObjectInterface;
use Magium\Util\Configuration\ConfigurationCollector\DefaultPropertyCollector;
use Magium\Util\Configuration\StandardConfigurationProvider;
use Magium\Util\Translator\Translator;
use Magium\Util\Translator\TranslatorAware;

abstract class AbstractConfigurableElement implements TranslatorAware, ConfigurableObjectInterface
{
    protected $translator;
    protected $collector;

    public function __construct(StandardConfigurationProvider $configurationProvider, DefaultPropertyCollector $collector)
    {
        $configurationProvider->configureObject($this);
        $this->collector = $collector;
    }

    public function __set($name, $value)
    {
        // Completely overriding PPP
        $this->$name = $value;
    }

    public function __get($name)
    {
        // Completely overriding PPP
        return $this->$name;
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

    public function get($key)
    {
        return $this->$key;
    }

    public function set($key, $value)
    {
        $this->$key = $value;
    }

    public function getDeclaredOptions()
    {
        return $this->collector->extract($this);
    }

}