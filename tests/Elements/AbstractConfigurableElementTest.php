<?php

namespace Tests\Magium\Elements;

use Magium\AbstractConfigurableElement;
use Magium\AbstractTestCase;
use Magium\Cli\CommandLoader;
use Magium\Util\Configuration\BypassConfigurationProvider;
use Magium\Util\Configuration\ConfigurationCollector\DefaultPropertyCollector;
use Magium\Util\Configuration\ConfigurationReader;
use Magium\Util\Configuration\StandardConfigurationProvider;
use Magium\Util\Translator\Translator;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class AbstractConfigurableElementTest extends AbstractTestCase
{


    protected $baseDir;

    protected function setUp()
    {
        parent::setUp();
        $this->baseDir = sys_get_temp_dir();
    }

    protected function getCliConfigFilename()
    {
        return $this->baseDir . '/magium.json';
    }

    protected function getConfiguredApplication()
    {
        $application = new Application();
        $loader = new CommandLoader($application, $this->baseDir);
        $loader->load();
        return $application;
    }

    protected function tearDown()
    {
        if (file_exists($this->getCliConfigFilename())) {
            unlink($this->getCliConfigFilename());
        }
        parent::tearDown();
    }

    public function testPropertyPassedViaEnvironmentVariable()
    {
        $_ENV['MAGIUM_TESTS_MAGIUM_ELEMENTS_PROPERTYELEMENT_property'] = 'changed';
        $obj =  new PropertyElement(new StandardConfigurationProvider(new ConfigurationReader()), new DefaultPropertyCollector());
        $obj->setTranslator(new Translator());
        self::assertEquals('changed', $obj->getProperty());
    }

    public function testTranslationSmokeTest()
    {
        $obj =  new PropertyElement(new StandardConfigurationProvider(new ConfigurationReader()), new DefaultPropertyCollector());
        $obj->setTranslator($this->getTranslator());
        $value = $obj->translatePlaceholders('{{Kevin}}');
        self::assertEquals('Kevin', $value);

        $value = $obj->translatePlaceholders('Kevin');
        self::assertEquals('Kevin', $value);

        $value = $obj->translatePlaceholders('Kevin}');
        self::assertEquals('Kevin}', $value);
    }

    public function testInjection()
    {
        $obj = $this->get('Tests\Magium\Elements\PropertyElement');
        self::assertInstanceOf('Magium\Util\Translator\Translator', $obj->getTranslator());
    }

    public function testInclusion()
    {
        $obj =  new PropertyElement(new StandardConfigurationProvider(new ConfigurationReader(), __DIR__ . '/include-file.php'), new DefaultPropertyCollector());
        self::assertEquals(2, $obj->getValue());
        self::assertEquals(1, $obj->property);
    }

    public function testConfigurationProviderCanBeDisabled()
    {
        $provider = new BypassConfigurationProvider(new ConfigurationReader(), 'include-file.php');
        $obj =  new PropertyElement($provider, new DefaultPropertyCollector());
        self::assertNull($obj->getValue());
        self::assertEquals('original', $obj->property);
    }

    public function testJsonConfiguration()
    {
        $reader = new ConfigurationReader($this->baseDir);
        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $command = $application->find('magium:element:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'   => $command->getName(),
            'class'     => 'Tests\Magium\Elements\PropertyElement',
            'property'  => 'property',
            'value'     => 'boogee'
        ]);

        $obj = new PropertyElement(new StandardConfigurationProvider($reader), new DefaultPropertyCollector());
        self::assertEquals('boogee', $obj->getProperty());

    }


}

class PropertyElement extends AbstractConfigurableElement
{

    public $property = 'original';
    public $value = null;

    public function getProperty()
    {
        return $this->property;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}