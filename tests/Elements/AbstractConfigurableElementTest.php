<?php

namespace Tests\Magium\Elements;

use Magium\AbstractConfigurableElement;
use Magium\AbstractTestCase;
use Magium\Cli\CommandLoader;
use Magium\Extractors\AbstractExtractor;
use Magium\Extractors\ExtractorInterface;
use Magium\Themes\BaseThemeInterface;
use Magium\Themes\ThemeConfigurationInterface;
use Magium\Themes\ThemeInterface;
use Magium\Util\Configuration\AbstractConfigurationReader;
use Magium\Util\Configuration\BypassConfigurationProvider;
use Magium\Util\Configuration\ClassConfigurationReader;
use Magium\Util\Configuration\ConfigurableObjectInterface;
use Magium\Util\Configuration\ConfigurationCollector\DefaultPropertyCollector;
use Magium\Util\Configuration\ConfigurationReader;
use Magium\Util\Configuration\EnvironmentConfigurationReader;
use Magium\Util\Configuration\StandardConfigurationProvider;
use Magium\Util\Translator\Translator;
use Magium\WebDriver\WebDriver;
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
        $obj = new PropertyElement(new StandardConfigurationProvider(new ConfigurationReader(), new ClassConfigurationReader(), new EnvironmentConfigurationReader()), new DefaultPropertyCollector());
        $obj->setTranslator(new Translator());
        self::assertEquals('changed', $obj->getProperty());
    }

    public function testPropertyPassedViaEnvironmentVariableRecursive()
    {
        $_ENV['MAGIUM_TESTS_MAGIUM_ELEMENTS_PROPERTYELEMENT_property'] = 'changed';
        $obj = new RecursivePropertyElement(new StandardConfigurationProvider(new ConfigurationReader(), new ClassConfigurationReader(), new EnvironmentConfigurationReader()), new DefaultPropertyCollector());
        $obj->setTranslator(new Translator());
        self::assertEquals('changed', $obj->getProperty());
    }

    public function testTranslationSmokeTest()
    {
        $obj = new PropertyElement(new StandardConfigurationProvider(new ConfigurationReader(), new ClassConfigurationReader(), new EnvironmentConfigurationReader()), new DefaultPropertyCollector());
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
        $obj = new PropertyElement(new StandardConfigurationProvider(new ConfigurationReader(), new ClassConfigurationReader(), new EnvironmentConfigurationReader(), __DIR__ . '/include'), new DefaultPropertyCollector());
        self::assertEquals(2, $obj->getValue());
        self::assertEquals(1, $obj->property);
    }

    public function testInclusionRecursion()
    {
        /*
         * This test is different from the previous one because the RecursivePropertyElement extends PropertyElement
         * and so this is testing the recursive functionality in the abstract configurable class.  So the test result
         * *should* be the same, but the value is retrieved by descending through the class hierarchy.
         */
        $obj = new RecursivePropertyElement(new StandardConfigurationProvider(new ConfigurationReader(), new ClassConfigurationReader(), new EnvironmentConfigurationReader(), __DIR__ . '/include'), new DefaultPropertyCollector());
        self::assertEquals(2, $obj->getValue());
        self::assertEquals(1, $obj->property);
    }

    public function testConfigurationProviderCanBeDisabled()
    {
        $provider = new BypassConfigurationProvider(new ConfigurationReader(), new ClassConfigurationReader(), new EnvironmentConfigurationReader(), 'include');
        $obj = new PropertyElement($provider, new DefaultPropertyCollector());
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

        $command = $application->find('element:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'class' => 'Tests\Magium\Elements\PropertyElement',
            'property' => 'property',
            'value' => 'boogee'
        ]);

        $obj = new PropertyElement(new StandardConfigurationProvider($reader, new ClassConfigurationReader(), new EnvironmentConfigurationReader()), new DefaultPropertyCollector());
        self::assertEquals('boogee', $obj->getProperty());

    }

    public function testJsonConfigurationRecursive()
    {
        $reader = new ConfigurationReader($this->baseDir);
        $application = $this->getConfiguredApplication();
        $command = $application->find('magium:init');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $command = $application->find('element:set');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'class' => 'Tests\Magium\Elements\PropertyElement',
            'property' => 'property',
            'value' => 'boogee'
        ]);

        $obj = new RecursivePropertyElement(new StandardConfigurationProvider($reader, new ClassConfigurationReader(), new EnvironmentConfigurationReader()), new DefaultPropertyCollector());
        self::assertEquals('boogee', $obj->getProperty());

    }

    public function testClassInterfaceInheritanceExtracted()
    {
        $configurationReader = new TestConfigurationReader();
        $configurableObject = new TestConfigurableObject(
            $this->getMockBuilder(WebDriver::class)->disableOriginalConstructor()->getMock(),
            $this,
            $this->createMock(ThemeConfigurationInterface::class)
        );

        $result = $configurationReader->configure($configurableObject);
        self::assertTrue(in_array(ThemeInterface::class, $result), 'Missing the ThemeInterface extraction');
        self::assertTrue(in_array(BaseThemeInterface::class, $result), 'Missing the BaseThemeInterface extraction');
        self::assertTrue(in_array(ExtractorInterface::class, $result), 'Missing the ExtractorInterface extraction');
        self::assertTrue(in_array(AbstractExtractor::class, $result), 'Missing the AbstractExtractor extraction');
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


class RecursivePropertyElement extends PropertyElement
{
}

class TestConfigurationReader extends AbstractConfigurationReader
{

    public function configure(ConfigurableObjectInterface $object)
    {
        return $this->introspectClass($object);
    }

}

class TestConfigurableObject extends AbstractExtractor implements ConfigurableObjectInterface, BaseThemeInterface
{
    public function extract()
    {

    }

    public function getHomeXpath()
    {
    }

    public function configure(AbstractTestCase $testCase)
    {
    }

    public function get($key)
    {
    }

    public function set($key, $value)
    {
    }

    public function getDeclaredOptions()
    {
    }

    public function getGuaranteedPageLoadedElementDisplayedXpath()
    {
    }

    public function setGuaranteedPageLoadedElementDisplayedXpath($xpath)
    {
    }


}
