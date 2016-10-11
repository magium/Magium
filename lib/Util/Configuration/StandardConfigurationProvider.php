<?php

namespace Magium\Util\Configuration;

use Zend\Di\Di;

class StandardConfigurationProvider implements ConfigurationProviderInterface
{
    protected $configurationDir;

    protected $classConfigurationReader;

    protected $configurationReader;

    protected $environmentConfigurationReader;

    public function __construct(
        ConfigurationReader $configurationReader,
        ClassConfigurationReader $classConfigurationReader,
        EnvironmentConfigurationReader $environmentConfigurationReader,
        $configurationDir = null)
    {
        $this->configurationReader = $configurationReader;
        $this->classConfigurationReader = $classConfigurationReader;
        $this->environmentConfigurationReader = $environmentConfigurationReader;
        $this->configurationDir = $configurationDir;
        if ($this->configurationDir !== null && realpath($this->configurationDir) !== false) {
            $this->classConfigurationReader->setConfigurationDir($this->configurationDir);
        }
    }

    public function configureDi(Di $di)
    {
        $di->instanceManager()->addSharedInstance($this->configurationReader,               get_class($this->configurationReader));
        $di->instanceManager()->addSharedInstance($this->classConfigurationReader,          get_class($this->classConfigurationReader));
        $di->instanceManager()->addSharedInstance($this->environmentConfigurationReader,    get_class($this->environmentConfigurationReader));
        $di->instanceManager()->addSharedInstance($this,                                    get_class($this));
    }

    public function configureObject(ConfigurableObjectInterface $obj)
    {
        $this->classConfigurationReader->configure($obj);

        $this->configurationReader->configure($obj);

        $this->environmentConfigurationReader->configure($obj);
    }

}