<?php

namespace Magium\TestCase;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Interop\Container\ContainerInterface;
use Magium\AbstractTestCase;
use Magium\InvalidConfigurationException;
use Magium\TestCaseConfiguration;
use Magium\Util\Configuration\ConfigurationCollector\DefaultPropertyCollector;
use Magium\Util\Configuration\ConfigurationProviderInterface;
use Magium\Util\Configuration\StandardConfigurationProvider;
use Magium\Util\Log\Logger;
use Magium\Util\Log\LoggerInterface;
use Magium\Util\Log\LoggerPHPUnit5;
use Magium\Util\Phpunit\MasterListenerInterface;
use Magium\Util\TestCase\RegistrationListener;
use Magium\WebDriver\LoggingRemoteExecuteMethod;
use Magium\WebDriver\WebDriver;
use Zend\Di\Config;
use Zend\Di\Di;
use Zend\I18n\Translator\Translator;
use Zend\Log\Writer\Noop;

class Initializer
{

    protected $testCaseConfiguration = 'Magium\TestCaseConfiguration';
    protected $testCaseConfigurationObject;
    protected $initialized;
    protected $configurationProvider;
    protected static $initDi;

    public function __construct(
        $testCaseConfigurationType = null,
        TestCaseConfiguration $object = null,
        ConfigurationProviderInterface $configurationProvider = null
    )
    {
        if ($testCaseConfigurationType !== null) {
            $this->testCaseConfiguration = $testCaseConfigurationType;
        }
        if ($object instanceof TestCaseConfiguration) {
            $this->testCaseConfigurationObject = $object;
        }

        $this->configurationProvider = $configurationProvider;
        if (!$this->configurationProvider instanceof ConfigurationProviderInterface) {
            $preference = self::getInitializationDependencyInjectionContainer()->instanceManager()->getTypePreferences(ConfigurationProviderInterface::class);
            if (is_array($preference) && count($preference)) {
                $preference = array_shift($preference);
            } else {
                $preference = StandardConfigurationProvider::class;
            }
            $this->configurationProvider = self::getInitializationDependencyInjectionContainer()->get($preference);
        }
    }

    public static function setInitializationDependencyInjectionContainer(ContainerInterface $container)
    {
        self::$initDi = $container;
    }

    /**
     * This method returns a DI container that will ONLY be used to initialize this class
     *
     * @return Di
     */

    public static function getInitializationDependencyInjectionContainer()
    {
        if (!self::$initDi instanceof Di) {
            self::$initDi = new Di();
            self::$initDi->instanceManager()->addTypePreference(ConfigurationProviderInterface::class, StandardConfigurationProvider::class);
        }
        return self::$initDi;
    }

    protected function injectTestCaseHierarchy(AbstractTestCase $testCase)
    {
        $rc = new \ReflectionClass($testCase);
        while ($rc->getParentClass()) {
            $class = $rc->getParentClass()->getName();
            $testCase->getDi()->instanceManager()->addSharedInstance($testCase, $class);
            $rc = new \ReflectionClass($class);
        }
    }

    protected function configureWebDriver(AbstractTestCase $testCase)
    {
        $webDriver = $testCase->getDi()->get('Magium\WebDriver\WebDriver');
        if ($webDriver instanceof WebDriver) {
            $testCase->setWebdriver($webDriver);
            $testCase->setTypePreference(
                \Facebook\WebDriver\WebDriver::class,
                \Magium\WebDriver\WebDriver::class
            );
            $testCase->setTypePreference(
                RemoteWebDriver::class,
                \Magium\WebDriver\WebDriver::class
            );
        } else {
            throw new InvalidConfigurationException('DIC has misconfigured WebDriver object');
        }
    }

    protected function initLoggingExecutor(AbstractTestCase $testCase)
    {
        $remote = $testCase->getDi()->get(LoggingRemoteExecuteMethod::class);
        if ($remote instanceof LoggingRemoteExecuteMethod) {
            $testCase->getWebdriver()->setRemoteExecuteMethod($remote);
        } else {
            throw new InvalidConfigurationException('DIC has invalid logger configured');
        }
    }

    protected function executeCallbacks(AbstractTestCase $testCase)
    {
        RegistrationListener::executeCallbacks($testCase);
    }

    protected function setCharacteristics(AbstractTestCase $testCase)
    {
        $capabilities = $testCase->getWebdriver()->getCapabilities();
        $testCase->getLogger()->addCharacteristic(LoggerInterface::CHARACTERISTIC_BROWSER, $capabilities->getBrowserName());
        $testCase->getLogger()->addCharacteristic(LoggerInterface::CHARACTERISTIC_BROWSER_VERSION, $capabilities->getVersion());
        $testCase->getLogger()->addCharacteristic(LoggerInterface::CHARACTERISTIC_OPERATING_SYSTEM, $capabilities->getPlatform());
    }

    protected function attachMasterListener(AbstractTestCase $testCase)
    {
        $masterListener = AbstractTestCase::getMasterListener();
        $testCase->getDi()->instanceManager()->addSharedInstance($masterListener, MasterListenerInterface::class);
    }

    public function initialize(AbstractTestCase $testCase, $force = false)
    {
        if ($this->initialized === $testCase && !$force) {
            return;
        }
        $this->configureDi($testCase);
        $this->attachMasterListener($testCase);
        $this->injectTestCaseHierarchy($testCase);
        $this->configureWebDriver($testCase);
        $this->initLoggingExecutor($testCase);
        $this->executeCallbacks($testCase);
        $this->setCharacteristics($testCase);
        $this->initialized = $testCase;
    }

    protected function getDefaultConfiguration()
    {
        // Choose the correct logger/listener for the version of PHPUnit being used
        $loggerClass = class_exists('PHPUnit_Framework_TestCase')?LoggerPHPUnit5::class:Logger::class;

        return [
            'definition' => [
                'class' => [
                    'Magium\WebDriver\WebDriver' => [
                        'instantiator' => 'Magium\WebDriver\WebDriverFactory::create'
                    ],

                    'Magium\WebDriver\WebDriverFactory' => [
                        'create'       => $this->testCaseConfigurationObject->getWebDriverConfiguration()
                    ]
                ]
            ],
            'instance'  => [
                'preference' => [
                    Translator::class => [\Magium\Util\Translator\Translator::class],
                    \Zend\Log\LoggerInterface::class => [$loggerClass],
                    LoggerInterface::class => [$loggerClass],
                ],
                'Magium\Util\Translator\Translator' => [
                    'parameters'    => [
                        'locale'    => 'en_US'
                    ]
                ],
                $loggerClass   => [
                    'parameters'    => [
                        'options'   => [
                            'writers' => [
                                [
                                    'name' => Noop::class,
                                    'options' => []
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function configureDi(AbstractTestCase $testCase)
    {

        if (!$this->testCaseConfigurationObject instanceof TestCaseConfiguration) {
            if ($testCase->getDi() instanceof Di) {

                $testCaseConfiguration = $testCase->get($this->testCaseConfiguration);
                if ($testCaseConfiguration instanceof TestCaseConfiguration) {
                    $this->testCaseConfigurationObject = $testCaseConfiguration;
                }
            } else {
                $this->testCaseConfigurationObject = new $this->testCaseConfiguration(
                    $this->configurationProvider
                    , new DefaultPropertyCollector()
                );
            }
        }
        if ($testCase->getDi() instanceof Di) {
            return;
        }
        /* @var $configuration TestCaseConfiguration */
        $configArray = $this->getDefaultConfiguration();

        $count = 0;

        $path = realpath(__DIR__ . '/../');

        while ($count++ < 5) {
            $dir = "{$path}/configuration/";
            if (is_dir($dir)) {
                foreach (glob($dir . '*.php') as $file) {
                    $configArray = array_merge_recursive($configArray, include $file);
                }
                break;
            }
            $path .= '/../';
        }


        $configArray = $this->testCaseConfigurationObject->reprocessConfiguration($configArray);
        $configuration = new Config($configArray);

        $di = new Di();
        $configuration->configure($di);
        $testCase->setDi($di);
        $di->instanceManager()->addSharedInstance($di, Di::class);

        $this->setConfigurationProvider($testCase);
    }

    public function setConfigurationProvider(AbstractTestCase $testCase)
    {
        $configuredProvider = self::getInitializationDependencyInjectionContainer()->instanceManager()->getTypePreferences(ConfigurationProviderInterface::class);
        if (is_array($configuredProvider) && count($configuredProvider)) {
            $configuredProvider = array_shift($configuredProvider);
        } else {
            $configuredProvider = StandardConfigurationProvider::class;
        }
        $testCase->setTypePreference(
            ConfigurationProviderInterface::class,
            $configuredProvider
        );
        $this->configurationProvider->configureDi($testCase->getDi());

    }

}
