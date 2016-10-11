<?php

namespace Tests\Magium\AbstractTestCase;

use Magium\AbstractTestCase;
use Magium\TestCase\Initializer;
use Magium\TestCaseConfiguration;
use Magium\Util\Configuration\ClassConfigurationReader;
use Magium\Util\Configuration\ConfigurationCollector\DefaultPropertyCollector;
use Magium\Util\Configuration\ConfigurationReader;
use Magium\Util\Configuration\EnvironmentConfigurationReader;
use Magium\Util\Configuration\StandardConfigurationProvider;

class TestAlternateConfigDir extends AbstractTestCase
{
    public function __construct($name = null, array $data = [], $dataName = null, Initializer $initializer = null)
    {
        $initializer = new Initializer(
            null,
            null,
            new StandardConfigurationProvider(
                new ConfigurationReader(),
                new ClassConfigurationReader(realpath(__DIR__ . '/test_configuration')),
                new EnvironmentConfigurationReader()
            )
        );

        parent::__construct($name, $data, $dataName, $initializer);

    }

    public function testAlternateConfigFileLoaded()
    {
        self::assertEquals('alternate_value', $this->get(ArbitraryConfigurableElement::class)->get('arbitrary_key'));
    }
}