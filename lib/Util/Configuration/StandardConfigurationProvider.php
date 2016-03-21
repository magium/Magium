<?php

namespace Magium\Util\Configuration;

class StandardConfigurationProvider
{
    protected $configurationFile;

    protected $classConfigurationReader;

    protected $configurationReader;

    protected $environmentConfigurationReader;

    public function __construct(
        ConfigurationReader $configurationReader,
        ClassConfigurationReader $classConfigurationReader,
        EnvironmentConfigurationReader $environmentConfigurationReader,
        $configurationFile = null)
    {
        $this->configurationReader = $configurationReader;
        $this->classConfigurationReader = $classConfigurationReader;
        $this->environmentConfigurationReader = $environmentConfigurationReader;
        $this->configurationFile = $configurationFile;
    }

    public function configureObject(ConfigurableObjectInterface $obj)
    {
        $this->classConfigurationReader->setConfigurationFile($this->configurationFile);
        $this->classConfigurationReader->configure($obj);

        $this->configurationReader->configure($obj);

        $this->environmentConfigurationReader->configure($obj);
    }

}